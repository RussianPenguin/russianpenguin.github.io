# -*- encoding: utf-8 -*- from __future__ import unicode_literals

import shortestKey as sk
import re
import math
from sys import argv

def str_rshift(str, n):
	n = n % len(str)
	n = len(str) - n
	return str[n:] + str[:n]

def calc_match(input1, input2):
	matches = 0
	for idx in range(len(input1)):
		if input1[idx] == input2[idx]:
			matches += 1
	return (100.0*matches)/len(input1)

def detect_key_len(coded_text):
	decoded = []
	for key_len in range(1, len(coded_data)/2):
		
		shifted_text = str_rshift(coded_data, key_len)
		info = {
			'len': key_len,
			'match': calc_match(coded_data, shifted_text),
			'shifted': shifted_text
		}
		decoded += [info]

	key_info = sorted(decoded, key = lambda item: item['match'], reverse = True)[0]
	return key_info['len']

def detect_possible_key(coded_text, key_len):
	# split text into groups based on founded key len and xor with ' ' (space)
	groups = [coded_data[i:i+key_len] for i in range(0, len(coded_data), key_len)]

	possible_key = ''
	for i in range(key_len):
		freq = {}
		scaned_chars = 0
		for item in groups:
			# check if text part not less that current index
			if i < len(item):
				char = sk.str_xor(item[i], ' ')
				if char in freq:
					freq[char] += 1
				else:
					freq[char] = 1
				scaned_chars += 1
		
		freq = {k: float(v)/scaned_chars for k, v in freq.items()}
		key_char = sorted(freq.items(), key = lambda item: item[1], reverse = True)[0]
		possible_key += key_char[0]
	return possible_key		


if __name__ == '__main__':
	coded_data = ''

	with open(argv[1], 'rb') as f:
		coded_data = f.read()

	# find key length
	key_len = detect_key_len(coded_data)
	possible_key = detect_possible_key(coded_data, key_len)

	print sk.str_xor(coded_data, possible_key)
	print "This text encoded by key: %s" % (possible_key)
