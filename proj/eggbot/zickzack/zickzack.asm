.include "m8def.inc"

; Temporary Register.
.def temp = r16
; Loop counter A.
.def loopa = r17
; Loop counter B.
.def loopb = r18
; Tower position in half-steps.
.def tpos = r19
; Step sequence position rotation.
.def stepr = r20
; Step sequence position tower.
.def stept = r21
; One.
.def one = r22
ldi one, 1
; Min tower position.
.def posmin = r23
ldi posmin, 0x00
; Max tower position.
.def posmax = r24
ldi posmax, 0xff

; Initialize Stack.
ldi     temp, LOW(RAMEND)
out     SPL, temp
ldi     temp, HIGH(RAMEND)
out     SPH, temp

; Activate ports.
ldi temp, 0xff
out DDRC, temp
out DDRD, temp

; Initialize positions.
ldi stepr, 8
ldi stept, 8
ldi tpos, 0

add r25, one
brcs end

; Main loop that simply rotates the egg. 21st century technology.
end:

;.include "zickzack.txt"

.include "sechseck.txt"

;rcall moverr

rjmp end

; Here cometh thy subroutines. "End-user" commands are:
; mover{l,r}: move the rotator left (counter-clockwise) or right (clockwise), i.e. rotate the egg.
; movet{r,l}: move the tower left or right (when viewing from "outside" of the bot).

moverr:
mov temp, stepr
rcall mover
mov stepr, temp
rcall setport
ret

moverl:
mov temp, stepr
rcall movel
mov stepr, temp
rcall setport
ret

movetr:
mov temp, stept
rcall mover
mov stept, temp
rcall setport
ret

movetl:
mov temp, stept
rcall movel
mov stept, temp
rcall setport
ret

mover:
ldi ZL, low(movejumpsr)
ldi ZH, high(movejumpsr)
add ZL, temp
ldi temp, 0
adc ZH, temp
ijmp
movejumpsr:
nop
rjmp move09r
rjmp move06r
nop
rjmp move05r
rjmp move01r
rjmp move04r
nop
rjmp move0ar
rjmp move08r
rjmp move02r
move09r:
ldi temp, 0x09
rjmp moveendr
move06r:
ldi temp, 0x06
rjmp moveendr
move05r:
ldi temp, 0x05
rjmp moveendr
move01r:
ldi temp, 0x01
rjmp moveendr
move04r:
ldi temp, 0x04
rjmp moveendr
move0ar:
ldi temp, 0x0a
rjmp moveendr
move08r:
ldi temp, 0x08
rjmp moveendr
move02r:
ldi temp, 0x02
rjmp moveendr
moveendr:
ret

movel:
ldi ZL, low(movejumpsl)
ldi ZH, high(movejumpsl)
add ZL, temp
ldi temp, 0
adc ZH, temp
ijmp
movejumpsl:
nop
rjmp move05l
rjmp move0al
nop
rjmp move06l
rjmp move04l
rjmp move02l
nop
rjmp move09l
rjmp move01l
rjmp move08l
move09l:
ldi temp, 0x09
rjmp moveendl
move06l:
ldi temp, 0x06
rjmp moveendl
move05l:
ldi temp, 0x05
rjmp moveendl
move01l:
ldi temp, 0x01
rjmp moveendl
move04l:
ldi temp, 0x04
rjmp moveendl
move0al:
ldi temp, 0x0a
rjmp moveendl
move08l:
ldi temp, 0x08
rjmp moveendl
move02l:
ldi temp, 0x02
rjmp moveendl
moveendl:
ret

setport:
out PORTC, stepr
out PORTD, stept
rcall dowait
ret

dowait:
ldi loopa, 0
ldi loopb, 240
loopmarka:
add loopa, one
brcs loopmarkb
rjmp loopmarka
loopmarkb:
ldi loopa, 220
add loopb, one
brcs loopend
rjmp loopmarka
loopend:
ret

