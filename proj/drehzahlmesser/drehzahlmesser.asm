.include "m8def.inc"

.org 0x000
       rjmp RESET
.org INT0addr                 ; External Interrupt0 Vector Address
       rjmp int0handler                   
.org INT1addr                 ; External Interrupt1 Vector Address
       reti                   
.org OVF0addr
        rjmp    timer0_overflow       ; Timer Overflow Handler
                   
 
.org INT_VECTORS_SIZE
RESET:                        ; hier beginnt das Hauptprogramm 

; Initialize Stack.
ldi     temp, LOW(RAMEND)
out     SPL, temp
ldi     temp, HIGH(RAMEND)
out     SPH, temp

.def temp = r16

.def one = r17
ldi one, 1

.def speed1 = r18
.def speed2 = r19
.def out1 = r20
.def out2 = r21

         ldi temp, 0x00
         out DDRD, temp
 
         ldi temp, 0xFF
         out DDRB, temp
 
         ldi temp, 0b00001010  ; INT0 und INT1 konfigurieren
         out MCUCR, temp
 
         ldi temp, 0b11000000  ; INT0 und INT1 aktivieren
         out GICR, temp

        ldi     temp, 0b00000101      ; CS00 setzen: Teiler 1024
        out     TCCR0, temp
 
        ldi     temp, 0b00000001      ; TOIE0: Interrupt bei Timer Overflow
        out     TIMSK, temp

 
         sei                   ; Interrupts allgemein aktivieren


mainloop:
rjmp mainloop

int0handler:
	add speed1, one
	brcs overflow
	reti
	overflow:
	add speed2, one
reti

timer0_overflow:

reti
