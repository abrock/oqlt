.include "m8def.inc"

; Initialize Stack.
ldi     temp, LOW(RAMEND)
out     SPL, temp
ldi     temp, HIGH(RAMEND)
out     SPH, temp

.def temp = r16

; Anzahl Versuche:
.def versuche = r17

; Kontakte:
; 0 = Einschalter (Reset)
; 1 = Draht
; 2 = Anfang
; 3 = Ende
.def kontakte = r18

; Status:
; 0 1 = Spiel läuft
; 0 0 = Spiel läuft nicht
; 1 1 = Draht berührt
; 2 1 = Fertig
.def status = r19

; Ports konfigurieren:
ldi temp, 0
out DDRC, temp

ldi temp, 0xff
out DDRD, temp
out PORTC, temp

mainloop:
rcall anzeige

in kontakte, PINC
com kontakte

; if (Spiel läuft nicht and Start-Taste nicht gedrückt) {
mov temp, kontakte
or temp, status
sbrs temp, 0
	rjmp mainloop
; }
; else { Setze Status, so dass spiel läuft
	ori status, 0b00000001
; }

; if (Reset-Knopf gedrückt) {
sbrc kontakte, 0
ldi status, 0b00000001
sbrc kontakte, 0
ldi versuche, 0
;}

; if (gewonnen) { ignoriere alles
sbrc status, 2
rjmp mainloop
; }

; if (anfang berührt) { Setze Status
sbrc kontakte, 2
andi status, 0b11111101
; }

; if (heiser draht berührt und status nicht gesetzt) {
; Inkrementiere Zähler
mov temp, status
com temp
and temp, kontakte
sbrc temp, 1
inc versuche
; }

; if (heiser draht berührt) { Setze Status
sbrc kontakte, 1
ori status, 0b00000010
;}

; if (heiser draht nicht berührt und am ende) { setze status
mov temp, status
lsl temp
lsl temp
com temp
and temp, kontakte
sbrc temp, 3
ori status, 0b00000100
; }

rjmp mainloop

anzeige:



ret
