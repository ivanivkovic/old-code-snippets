'''

The script generates a random string by sequence, which is done by converting the iteration variable to a list of base-n numbers, which then serve as a keymap for the string pool. I.e. decimal int > base-n list > correct linear string generation.

Example of 3 letter sequencing (string pool has only 3 letters), for strings with 2 chars or 3 chars in length.

Conversion is to the base 3 system (since 3 letters are in the string pool).

1  00 aa
2  01 ab
3  02 ac
4  10 ba
5  11 bb
6  12 bc
7  20 ca
8  21 cb
9  22 cc

1   000 aaa
2   001 aab
3   002 aac
4   010 aba
5   011 abb
6   012 abc
7   020 aca
8   021 acb
9   022 acc
10  100 baa

'''

import string

string_pool = "abc"
string_range = [2, 4]

# Converts an integer number to a base x system number.
# Optional parameter: String defines replacement for the base x number (to generate a string list, otherwise we generate a base-n int list).
# Optional parameter: Order - allows us to toggle digit / string ordering. 1 = invert order
def intToBaseInfString(num, base, length, pool='', order = 0):

    digits = []

    while num:
        append = pool[int(num % base)] if pool != '' else int(num % base)
        digits.append( append )
        num //= base

    while( len(digits) < length ):
        append = pool[0] if pool != '' else 0
        digits.append( append )

    if order == 1:
        return digits[::-1]

    return digits

string_pool_len = len(string_pool)

# Iterating possible string lengths
for max_len in range( string_range[0], string_range[1] + 1 ):

    string = [string_pool[0]] * max_len

    iteration = 0

    # Iterating from 0 to (string pool length times maximum string length) with a single iteration variable.
    # This allows us to track our progress correctly, without defining any additional variables, loops or conditionals.
    while (iteration < string_pool_len ** max_len):

        string = intToBaseInfString(iteration, string_pool_len, max_len, '', 1)
        iteration += 1

        print(string)
