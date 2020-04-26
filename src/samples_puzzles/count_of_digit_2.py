#!/usr/bin/python
# -*- coding: utf-8 -*-

import math

# Считаем количество цифр в числах от 0 до N

def main():
	n = get_n()
	#count = calc_count(n)
	count = calc_count_2(n)
	print u'Количество цифр 2: ', count

def get_n():
	return int(input("Введите число: "))

def calc_count(n):
	current = 0
	total_count = 0
	while current <= n:
		temporary_num = current
		while temporary_num > 0:
			total_count += temporary_num % 10 == 2
			temporary_num /= 10
		current += 1
	return total_count
	
def calc_count_2(n):
	position = len(str(n)) - 1
	num_count = 0
	while position >= 0:
		num_count += calc_count_for_position(position, n)
		position -= 1
	return num_count

def calc_count_for_position(position, n):
	power_of_10 = int(math.pow(10, position))
	next_power_of_10 = power_of_10 * 10
	right = n % power_of_10
	round_down = n - n % next_power_of_10
	round_up = round_down + next_power_of_10
	digit = (n / power_of_10) % 10
    
	if digit < 2:
		return round_down / 10
	elif digit == 2:
		return round_down / 10 + right + 1
	elif digit > 2:
		return round_up / 10
    

main()

