import itertools
import random


def generate_anagrams(input_string, num_anagrams):
    anagrams = set()
    input_list = list(input_string)
    input_length = len(input_list)

    while len(anagrams) < num_anagrams:
        random.shuffle(input_list)
        anagram = ''.join(input_list)
        if anagram != input_string:
            anagrams.add(anagram)

    return list(anagrams)


input_string = "nicole"
num_anagrams = 20
anagram_list = generate_anagrams(input_string, num_anagrams)

for i, anagram in enumerate(anagram_list):
    print(f"Anagram {i + 1}: {anagram}")
