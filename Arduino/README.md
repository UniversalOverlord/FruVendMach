# How the Arduino bit was made

We modelled our program around this simple state machine:
![FSM Machine Image](https://github.com/UniversalOverlord/FruVendMach/blob/main/Documentation/Arduino-FSM.png)

The materials used were the following:
- RFID reader (non native library, available in rfid-master.zip folder)
- Wi-Fi module (ESP8266)
- LCD screen (16x4 squares) 
- 2 gear motors + 2 motor IC's (RB350600, specifically)
- 2 push buttons
- (Arduino power supply)

The schematics are below:
|   Component   |         Pin         |                                   Arduino Pin                                   |
|:-------------:|:-------------------:|:-------------------------------------------------------------------------------:|
|  RFID Reader  |         SDA         |                                    Digital 10                                   |
|               |         SCK         |                                    Digital 13                                   |
|               |         MOSI        |                                    Digital 11                                   |
|               |         MISO        |                                    Digital 12                                   |
|               |         IRQ         |                                   unconnected                                   |
|               |         RST         |                                    Digital 9                                    |
| Motor Shields |        Apple        |                                     Analog 4                                    |
|               |        Orange       |                                     Analog 5                                    |
|  WiFi Shield  |          Tx         |                                    Digital 3                                    |
|               | 1kΩ → Rx → 1kΩ →GND |                                    Digital 2                                    |
|   LCD Screen  |          RS         |                                    Digital 7                                    |
|               |          EN         |                                    Digital 6                                    |
|               |          D4         |                                    Digital 5                                    |
|               |          D5         |                                    Digital 4                                    |
|               |          D6         |                                     Analog 3                                    |
|               |          D7         |                                     Analog 2                                    |
|               |          Vo         |                                10k Potentiometer                                |
|    Buttons    |        Apple        |                                     Analog 0                                    |
|               |        Orange       |                                     Analog 1                                    |
|  Power Supply |         +5V         | Motor Shield Input; LCD Screen Input; Buttons*; Potentiometer of RFID's Vo Pin  |
|               |        +3.3V        |                             RFID Input; WiFi Shield                             |


*with 10k resistor connected between button ground and arduino ground
