# author: Zhanmin Liang
# data: 2021/04/20
# title: main script for maize m6A_WeakRM prediction
# Meng lab

test_sequence.fasta and test_sequence.txt are both sample file
You can change the input sequence file path in R script: full_script_web.R, line 6 :  
##input_fl <- readDNAStringSet("test_sequence.fasta", format = "fasta")

1. Run the main file: web_script.py in environment python 3.7.2
## NOTICE!
## Change the path of R programme on line 14:
## rscript=('/home/zhanmin/miniconda3/envs/python3.7/bin/Rscript full_script_web.R')
## version of R not lower than 3.6

2. Output is the percentage of m6A modification by prediction. 
## Normally, people set that more than 0.5 is positive.
## You can change your standard according to the actual situation.
