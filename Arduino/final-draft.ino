// this file does not contain the final code, and does not contain the connection to the database

#include "Arduino.h"
#include <LiquidCrystal.h>

#include <SPI.h>
#include <Ethernet.h>
#include <MFRC522.h>
 
#define SS_PIN 10
#define RST_PIN 9
#define APPLE 14
#define ORANGE 15
#define APPLEMOTOR 18
#define ORANGEMOTOR 19

static unsigned int state;
static unsigned long time;
const int rs = 7, en = 6, d4 = 5, d5 = 4, d6 = 17, d7 = 16;
unsigned long hex_num;

LiquidCrystal lcd(rs, en, d4, d5, d6, d7);
MFRC522 mfrc522(SS_PIN, RST_PIN);
int valOrange = 0; //not being pressed
int valApple = 0; //not being pressed

// replace the MAC address below by the MAC address 
byte mac[] = { 0x00, 0x00, 0x00, 0x00, 0x00, 0x00 };

EthernetClient client;

int    HTTP_PORT   = 80;
String HTTP_METHOD = "GET";

// replace with website IP and path name
char   HOST_NAME[] = "162.241.253.84"; 
String PATH_NAME   = "wp-content/myphpfiles/RFID.php";
String queryString = "?id=";

void setup() {
  //setup RFID
  Serial.begin(9600);   
  SPI.begin();      // initiate  SPI bus
  mfrc522.PCD_Init();   // initiate MFRC522
  
  //analogic pins
  pinMode(APPLE,INPUT);
  pinMode(ORANGE, INPUT);
  pinMode(APPLEMOTOR,OUTPUT);
  pinMode(ORANGEMOTOR, OUTPUT);
      
  // set up the LCD's number of columns and rows
  lcd.begin(16, 4);
  lcd.print("Ne tombe pas dans les pommes!");

  // initial state declaration and time reset
  state = 0;
  time = 0;
}

void loop() {
  // put your main code here, to run repeatedly:
  Serial.print("this is your state:   ");
  Serial.print(state);

  if (Ethernet.begin(mac) == 0) {
    Serial.println("Failed to obtaining an IP address using DHCP");
    while(true);
  }
  
  switch (state)
  {
    case 0:
      Serial.println("Approximate your card to the reader...");       
      Serial.println();
      
      //read RFID until finds a card
      while(1) {
          // Look for new cards
          if ( ! mfrc522.PICC_IsNewCardPresent()) 
          {
            continue;
          }
          
          // Select one of the cards
          if ( ! mfrc522.PICC_ReadCardSerial()) 
          {
            continue;
          }
          
        //Show UID on serial monitor
        Serial.print("UID tag :");
        String content= "";
        byte letter;
        for (byte i = 0; i < mfrc522.uid.size; i++) 
        {
          Serial.print(mfrc522.uid.uidByte[i] < 0x10 ? " 0" : " ");
          Serial.print(mfrc522.uid.uidByte[i], HEX);
          content.concat(String(mfrc522.uid.uidByte[i] < 0x10 ? " 0" : " "));
          content.concat(String(mfrc522.uid.uidByte[i], HEX));
          hex_num =  mfrc522.uid.uidByte[0] << 4*i;
        }
        
        // connect to web server on port 80:
        if(client.connect(HOST_NAME, HTTP_PORT)) {
          // if connected:
          Serial.println("Connected to server");
          // make a HTTP request:
          client.println(HTTP_METHOD + " " + PATH_NAME + queryString + hex_num + " HTTP/1.1"); // send HTTP header
          client.println("Host: " + String(HOST_NAME));
          client.println("Connection: close");
          client.println(); // end HTTP header
          
          while(client.connected()) {
            if(client.available()){
              // read an incoming byte from the server and print it to serial monitor:
              char c = client.read();
              Serial.print(c);
            }
         }
        }
    }

    // the server's disconnected, stop the client:
    client.stop();
    Serial.println();
    Serial.println("disconnected");
  } else {// if not connected:
    Serial.println("connection failed");
  }

        if (content.substring(1) != "04 35 09 BA 24 68 80") //everything's ok
        { 
          state = 1;
          break;
        }

        else if(content.substring(1) == "04 35 09 BA 24 68 80") //unauthorized card :o
        {
          lcd.clear();
          lcd.print("Carte non autorisee");
          delay(5000);
          lcd.clear();
          break;
        }
      } 
            
    case 1:
      Serial.println("Authorized access");
      Serial.println();
      {
        state = 2;
        time = millis();
      }
      break;
      
   case 2:
      lcd.clear();
      lcd.print("Orange ou Pomme?");
      valOrange = analogRead(ORANGE);
      valApple = analogRead(APPLE);
    
      if (valOrange >= 900) 
      {
        state = 5;
      }
      else if (valApple >= 900) 
      {
        state = 4;
      }
      else if (millis() - time > 10000) 
      {
        state = 0;
      }
      break;

   case 4:
      lcd.clear();
      lcd.print("Attendre la pomme");
      digitalWrite(APPLEMOTOR,HIGH);
      delay(3940);
      digitalWrite(APPLEMOTOR, LOW);
      lcd.clear();
      lcd.print("Ne tombe pas dans les pommes!");
      state = 0;
      break;

   case 5:
      lcd.clear();
      lcd.print("Attendre l'orange");
      digitalWrite(ORANGEMOTOR,HIGH);
      delay(3940);
      digitalWrite(ORANGEMOTOR, LOW);
      lcd.clear();
      lcd.print("Ne tombe pas dans les pommes!");
      state = 0;
      break;
  }
}
