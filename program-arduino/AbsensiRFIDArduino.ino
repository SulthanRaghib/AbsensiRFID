/*
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
#  Author : Sulthan Raghib Fillah                                     #
#  Date   : 31 Desember 2024                                          #
#  Desc.  : using RFID Card Reader to scan UID Card                   # 
#                                                                     #
#               Installation :                                        # 
#    NodeMCU ESP8266           RFID MFRC522                           #
#         D4       <---------->   SDA                                 #
#         D5       <---------->   SCK                                 #
#         D7       <---------->   MOSI                                #
#         D6       <---------->   MISO                                #
#         G        <---------->   GND                                 #
#         D1       <---------->   RST                                 #
#         3V       <---------->   3.3V                                #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
*/

#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>

#include <SPI.h>
#include <MFRC522.h>

// variabel untuk RFID
#define SDA_PIN 2 // D4
#define RST_PIN 0 // D3
#define KODE_SENSOR3 "3"
#define ON_Board_LED 2
#define PERMENIT 1
#define PERJAM 60
#define PERHARI 1440
#define APIKEY "806fd8a598b54e82301f414e1076638d"

MFRC522 mfrc522(SDA_PIN, RST_PIN);

// Network SSID
// const char* ssid = "BRIGADE JAYA";
// const char* password = "P3L0P0R*#34";
const char* ssid = "Dosen_Rooftop";
const char* password = "Dsn2024#!";
String apikey=APIKEY;
const char* serverName = "https://www.pemantauan.com/submission/";
unsigned long counting;

// pengenal host (server) = IP Address komputer
// const char* host = "192.168.18.36";
const char* host = "10.0.1.84";

// Variabel untuk melacak jumlah tap RFID dan waktu terakhir
int tapCount = 0;
unsigned long lastResetTime = 0;

void setup() {
  Serial.begin(115200);

  // setting koneksi wifi
  WiFi.hostname("NodeMCU");
  WiFi.begin(ssid, password);

  // cek koneksi wiFi
  while (WiFi.status() != WL_CONNECTED) {
    // mencoba koneksi
    delay(500);
    Serial.println(".");
  }

  Serial.println("Wifi Connected");
  Serial.println("IP Address : ");
  Serial.println(WiFi.localIP());

  SPI.begin();
  mfrc522.PCD_Init();
  Serial.println("Dekatkan kartu RFID anda ke Reader");
  Serial.println();

  // Simpan waktu saat perangkat mulai
  lastResetTime = millis();
}

void loop() {
  // Reset tapCount jika sudah lebih dari 1 hari
  if (millis() - lastResetTime >= 86400000) { // 86400000 ms = 1 hari
    tapCount = 0;
    lastResetTime = millis();
    Serial.println("Tap count telah direset karena sudah lebih dari 1 hari.");
  }

  int tapDetected = 0; // flag untuk mendeteksi tap kartu

  if (!mfrc522.PICC_IsNewCardPresent()) {
    tapDetected = 0; // Tidak ada kartu, set flag 0
  } else {
    if (mfrc522.PICC_ReadCardSerial()) {
      String IDTAG = "";
      for (byte i = 0; i < mfrc522.uid.size; i++) {
        IDTAG += String(mfrc522.uid.uidByte[i], HEX);
      }

      // Tampilkan IDTAG di Serial Monitor
      Serial.print("ID Kartu Terbaca: ");
      Serial.println(IDTAG);

      // Nyalakan lampu onboard
      digitalWrite(ON_Board_LED, HIGH);

      // Kirim nomor kartu RFID untuk disimpan ke tabel tmprfid
      WiFiClient client;
      const int httpPort = 80;
      if (!client.connect(host, httpPort)) {
        Serial.println("Connection Failed");
        return;
      }

      String Link = "/NativeRFID/kirim_kartu.php?no_kartu=" + IDTAG; // Perbaiki parameter GET
      HTTPClient http;
      http.begin(client, String("http://") + host + Link);

      int httpCode = http.GET();
      String payload = http.getString();
      Serial.println(payload);
      http.end();

      // Cek data kirim ke URL
      Serial.println("Request URL: http://" + String(host) + Link);

      tapCount = 1;
      Serial.println("Jumlah tap: " + String(tapCount));

      tapDetected = 1; // Tap terdeteksi, set flag ke 1
    }
  }

  // Memanggil fungsi aktifkanPemantauan dengan nilai 0 jika tidak ada tap
  if (tapDetected == 1) {
    aktifkanPemantauan(PERMENIT, tapCount);  // Atau gunakan PERJAM, PERHARI sesuai kebutuhan
  } else {
    aktifkanPemantauan(PERMENIT, 0);  // Kirim 0 jika tidak ada tap
    tapCount = 0; // Jika tidak ada tap, reset tapCount
    Serial.println("Tidak ada tap yang terdeteksi, mengirim nilai 0.");
  }

  delay(2000); // Delay untuk menghindari pembacaan terlalu cepat
}


// void loop() {
//   // Reset tapCount jika sudah lebih dari 1 hari
//   if (millis() - lastResetTime >= 86400000) { // 86400000 ms = 1 hari
//     tapCount = 0;
//     lastResetTime = millis();
//     Serial.println("Tap count telah direset karena sudah lebih dari 1 hari.");
//   }

//   if (!mfrc522.PICC_IsNewCardPresent())
//     return;
//   if (!mfrc522.PICC_ReadCardSerial())
//     return;

//   String IDTAG = "";
//   for (byte i = 0; i < mfrc522.uid.size; i++) {
//     IDTAG += String(mfrc522.uid.uidByte[i], HEX);
//   }

//   // Tampilkan IDTAG di Serial Monitor
//   Serial.print("ID Kartu Terbaca: ");
//   Serial.println(IDTAG);

//   // nyalakan lampu onboard
//   digitalWrite(ON_Board_LED, HIGH);

//   // kirim nomor kartu RFID untuk disimpan ke tabel tmprfid
//   WiFiClient client;
//   const int httpPort = 80;
//   if (!client.connect(host, httpPort)) {
//     Serial.println("Connection Failed");
//     return;
//   }

//   String Link = "/NativeRFID/kirim_kartu.php?no_kartu=" + IDTAG; // Perbaiki parameter GET
//   HTTPClient http;
//   http.begin(client, String("http://") + host + Link);

//   int httpCode = http.GET();
//   String payload = http.getString();
//   Serial.println(payload);
//   http.end();

//   // cek data kirim ke url
//   Serial.println("Request URL: http://" + String(host) + Link);

//   // Panggil fungsi aktifkanPemantauan di sini setelah membaca kartu
//   float value1 = 1; // Ganti dengan nilai yang sesuai
//   // tapCount += 1;
//   // Serial.println("Jumlah tap: " + String(tapCount));
//   aktifkanPemantauan(PERMENIT, value1);  // Atau gunakan PERJAM, PERHARI sesuai kebutuhan

//   delay(2000);
// }

void aktifkanPemantauan(int frekuensi, float value1) {
  // String obyek1 = String(SDA_PIN);
  String obyek1 = KODE_SENSOR3;
  int writeTimeRequired=60000;
  std::unique_ptr<BearSSL::WiFiClientSecure>client(new BearSSL::WiFiClientSecure);
  client->setInsecure();
  HTTPClient http;
  http.begin(*client, "https://www.pemantauan.com/submission/");
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  String httpRequestData = "apikey=" + apikey;
  httpRequestData = httpRequestData + "&obyek1=" + obyek1;
  httpRequestData = httpRequestData + "&value1=" + value1;
  int httpResponseCode = http.POST(httpRequestData);
  if (httpResponseCode > 0) {
    Serial.printf("Mengirim data... code: %d\n", httpResponseCode);
    const String& payload = http.getString();
      Serial.print("Respon server: ");
      Serial.println(payload);
    if (httpResponseCode == HTTP_CODE_OK) {
      const String& payload = http.getString();
      Serial.print("Respon server: ");
      Serial.println(payload);
      }
    } else {
      Serial.printf("Mengirim data... gagal, error: %s\n", http.errorToString(httpResponseCode).c_str());
      const String& payload = http.getString();
      Serial.print("Respon server: ");
      Serial.println(payload);
    }
    http.end();
}
