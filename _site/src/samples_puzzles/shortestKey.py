# -*- coding: utf8 -*-

from sys import argv
import re

def expand_key(key, length):
	return (key * (length/len(key) + 1))[:length]

def str_xor(input, key):
	output = ''
	key = expand_key(key, len(input))

	for i in range(len(input)):
		output += unichr(ord(input[i]) ^ ord(key[i]))

	return output

# find minimal pattern in string
def find_key(string):
	r = re.compile(r"(.+?)\1+")
	return min(r.findall(string) or [""], key=len)


if __name__ == '__main__':
	input = "creature_creature_creature"
	output = "]VTYJQC]aGC]_PDJ[{RJ[EEMLA"

	output =  str_xor(input, output)
	print output
	print find_key(output)
