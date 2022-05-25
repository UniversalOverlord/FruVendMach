#include "Arduino.h"
#include <LiquidCrystal.h>

#include <SPI.h>
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

LiquidCrystal lcd(rs, en, d4, d5, d6, d7);
MFRC522 mfrc522(SS_PIN, RST_PIN);
int valOrange = 0; //not being pressed
int valApple = 0; //not being pressed

void setup() {
  //setup RFID
  Serial.begin(9600);   
  SPI.begin();      // Initiate  SPI bus
  mfrc522.PCD_Init();   // Initiate MFRC522
  
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
