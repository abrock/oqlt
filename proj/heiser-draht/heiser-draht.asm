.include "m168def.inc"

.def temp = r16

.def one = r17
ldi one, 1

.def kontakte = r18

; Anzahl der Versuche, die ein Spieler gebraucht hat.
.def versuche1 = r19
.def versuche2 = r20

; Zust�nde. in denen sich die Spieler befinden
.def status = r21

; Ports konfiguerieren
ldi temp, 0
out DDRC, temp

ldi temp, 0xff
out DDRB, temp
out DDRD, temp
out PORTC, temp

rcall reset

reset:
ldi status, 0b00000000
;                ||||+- 0: Programm gestartet
;                |||+-- 1: Spieler 1 hat den hei�en Draht ber�hrt
;   			 ||+--- 2: Spieler 1 ist fertig
;                |+---- 3: Spieler 2 hat den hei�en Draht ber�hrt
;                +----- 4: Spieler 2 ist fertig

ldi versuche1, 0
ldi versuche2, 0

ret

; Initialize Stack.
ldi     temp, LOW(RAMEND)
out     SPL, temp
ldi     temp, HIGH(RAMEND)
out     SPH, temp

; Eingang (= kontakte):
; PC0: Spieler1 am Anfang
; PC1: Spieler1 ber�hrt hei�en Draht
; PC2: Spieler1 am Ende

; PC3: Spieler2 am Anfang
; PC4: Spieler2 ber�hrt hei�en Draht
; PC5: Spieler2 am Ende

; PC6: Taster f�r den Start wurde gedr�ckt

; Ausg�nge PortD und PortB
; PX 0-3: Anzahl Versuche
; PX 4  : Draht ber�hrt
; PX 5  : Fertig
; PX 6  : Gewonnen
; PX 7  : Spiel l�uft


mainloop:
; Frage PORTC ab, um herauszufinden, ob ein Spieler
; den Draht, den Anfang, oder das Ende ber�hrt hat.
in kontakte, PINC
com kontakte

; Setze die Z�hler und Zust�nde zur�ck, wenn der
; Reset-Knopf gedr�ckt wird:
SBRC kontakte, 0
rcall reset

; Springe an den Anfang dse Hauptprogramms, wenn weder
; der Startknopf gedr�ckt,
; noch das Programm im aktiven Zustand ist
ldi temp, 0
SBRC kontakte, 6
ori temp, 0b00000001

or temp, status
SBRS temp, 0
rjmp mainloop

ori status, 0b00000001
ori versuche1, 0b10000000
ori versuche2, 0b10000000

;####################################################
; Wenn Spieler 1 fertig ist, alles weitere ignorieren
SBRC status, 2
rjmp spieler1ende

  ; Wenn Spieler 1 den Anfang ber�hrt wird sein Status
  ; wieder auf spielen gesetzt.
  SBRC kontakte, 0
  andi versuche1, 0b11101111

  ; Wenn Spieler 1 den hei�en Draht ber�hrt hat springe zum Ende.
  SBRC status, 3
  rjmp spieler1ende

    ; Wenn Spieler 1 gerade eben den hei�en Draht ber�hrt hat:
    SBRC kontakte, 1
    rcall spieler1draht

	; Wenn Spieler 1 gerade fertig geworden ist:
	SBRC kontakte, 2
	rcall spieler1fertig

spieler1ende:

;####################################################
; Wenn Spieler 2 fertig ist, alles weitere ignorieren
SBRC status, 4
rjmp spieler2ende

  ; Wenn Spieler 2 den Anfang ber�hrt wird sein Status
  ; wieder auf spielen gesetzt.
  SBRC kontakte, 0
  andi versuche2, 0b11101111

  ; Wenn Spieler 2 den hei�en Draht ber�hrt hat:
  SBRC status, 3
  rjmp spieler2ende

    ; Wenn Spieler 2 gerade eben den hei�en Draht ber�hrt hat:
    SBRC kontakte, 4
    rcall spieler2draht

	; Wenn Spieler 2 gerade fertig geworden ist:
	SBRC kontakte, 5
	rcall spieler2fertig

spieler2ende:

; Setze die Ausg�nge
rcall setports

rjmp mainloop

;###################################################

spieler1draht:
; Setze den Status
ori status, 0b00000010
; Erh�he den Versuchsz�hler
inc versuche1

; �berpr�fe, ob der Z�hler auf 10 steht
SBRC versuche1, 0
ret
SBRS versuche1, 1
ret
SBRC versuche1, 2
ret
SBRS versuche1, 3
ret

; Wenn der Z�hler auf 10 steht hat der Gegenspieler gewonnen
; und das Programm geht wieder in den Start-Modus
ori versuche2, 0b01000000
andi status,    0b00000000

ret


spieler2draht:
; Setze den Status
ori status, 0b00001000
; Erh�he den Versuchsz�hler
inc versuche2

; �berpr�fe, ob der Z�hler auf 10 steht
SBRC versuche2, 0
ret
SBRS versuche2, 1
ret
SBRC versuche2, 2
ret
SBRS versuche2, 3
ret

; Wenn der Z�hler auf 10 steht hat der Gegenspieler gewonnen
; und das Programm geht wieder in den Start-Modus
ori versuche1, 0b01000000
andi status,    0b00000000

ret

setports:
out PORTB, versuche1
out PORTD, versuche2
ret

spieler1fertig:
ori status, 0b00000100
ori versuche1, 0b00100000
ret

spieler2fertig:
ori status, 0b00010000
ori versuche2, 0b00100000
ret
