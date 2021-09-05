library(Biostrings)
library(jsonlite)
#library(reticulate)

input_json <- commandArgs(trailingOnly = T)
jobID <- input_json[1]
a <- as.data.frame(fromJSON(paste0('/var/www/html/maize/job/',jobID,'/',jobID,'_para.json')))
file <- as.character(a$file)
setwd('/var/www/html/maize/script/')

  # read fasta file
  input_fl <- readDNAStringSet(file, format = "fasta")
  # read text data
  # input <- readLines("test_sequence.txt")
  # input <- as.list(input)
  # input <- input[-grep(">", input)]
  # input <- DNAStringSet(as.character(input))
  
  # transfrom sequences to n*4 matrix (0-1 matrix)
  str2token <- function(str_sequence) {
    splitted_seq <- strsplit(str_sequence, '') # split sequence in the data.frame
    token_seq <- vector(mode='list', length=length(splitted_seq)) # build the empty vector
    
    # translation the A,U,C,G into 1,2,3,4
    for (idx in c(1:length(splitted_seq))){
      token_seq[idx] = list(as.integer(unlist(splitted_seq[idx]) == 'A') +
                              2 * as.integer(unlist(splitted_seq[idx]) == 'C') +
                              3 * as.integer(unlist(splitted_seq[idx]) == 'G') +
                              4 * as.integer(unlist(splitted_seq[idx]) == 'U'))
    }
    return(token_seq)
  }
  
  input_seq <- RNAStringSet(input_fl)
  input_seq <- as.character(input_seq)
  input_seq <- str2token(input_seq)
  
  saveRDS(input_seq, paste0('/var/www/html/maize/job/',jobID,'/download/input_seq.rds'))

dir.create(paste0('/var/www/html/maize/job/',jobID,'/download/Results'))
d$jobID <- jobID
result <- system(paste0('/var/www/html/maize/script/web_script.py -s ',d$sequence,
                ' --save=True --save_path=/var/www/html/maize/job/',jobID,'/download/Results/'))

# use_condaenv("/home/zhanmin/miniconda3/envs/python3.7/bin/python")
# py_config()#安装的python版本环境查看，显示anaconda和numpy的详细信息。
# py_available()#[1] TRUE   #检查您的系统是否安装过Python
# py_module_available("pandas")#检查“pandas”是否安装

# 
# repl_python()
# import os
# import numpy as np
# import rpy2.robjects as ro
# from utils import embed
# 
# np.random.seed(323)
# 
# 
# data_dir = '/data/zhanmin/maize/preparation/predictdata/'
# target_dir = data_dir + 'npydata/'
# specie = 'maize_'
# if not os.path.exists(target_dir):
#   os.mkdir(target_dir)
# 
# instance_len = 40
# instance_stride = 5
# 
# test_seq = input_seq
# test_seq = np.asarray(test_seq)
# test_seq = [np.asarray(seq) for seq in test_seq]
# 
# test_bags = []
# for seq in test_seq:
#   ont_hot_bag = embed(seq, instance_len, instance_stride)
# test_bags.append(ont_hot_bag)
# 
# test_bags = np.asarray(test_bags)
