import os
import sys
import json
import numpy as np
import pandas as pd
from utils import embed
import itertools
from sklearn.metrics import accuracy_score, f1_score, precision_score, recall_score, matthews_corrcoef
from sklearn.metrics import roc_auc_score, average_precision_score
import tensorflow as tf
from nets import WSCNN, WSCNNLSTM, WeakRM, WeakRMLSTM
from prettytable import PrettyTable
import time
from Bio import SeqIO
import rpy2.robjects as ro

os.environ["CUDA_VISIBLE_DEVICES"] = "-1"

jobID = sys.argv[1]

rscript=('/usr/bin/Rscript /var/www/html/maize/traits_script/traits_web.R'+' '+jobID)
p=os.system(rscript)

np.random.seed(323)

# readRDS = ro.r['readRDS']
instance_len = 40
instance_stride = 5

# get the pre-paperated input data
ls = []
for root, dirs, files in os.walk("/var/www/html/maize/job2/"+jobID+"/"):
    for file in files:
      if file.endswith("_data_tmp.rds"):
        ls.append(os.path.join(root, file))
print(ls)
fl_n = []
for i in ls:
  name = i.split("_")
  fl_n.append(name[1])
if fl_n is None:
  print("There has not related m6A modification site for inputed SNP data./n")
  sys.exit()
  
def str2token(str_sequences):
  seq = [np.array(sequence.seq) for sequence in str_sequences]
  for i in range(len(seq)):
    seq[i][seq[i] == 'A'] = 1
    seq[i][seq[i] == 'C'] = 2
    seq[i][seq[i] == 'G'] = 3
    seq[i][seq[i] == 'T'] = 4
  seq = [[int(i) for i in s] for s in seq]
  return(seq)

print(fl_n)

for i in fl_n:
  ref_seq = list(SeqIO.parse("/var/www/html/maize/job2/"+jobID+"/download/snp_ref_" + i + "_tmp.fasta", "fasta"))
  ref_seq = str2token(ref_seq)
  # ref_seq = readRDS("/data/zhanmin/maize/traits_web/snp_ref_" + i + "_tmp.rds")
  # ref_seq = np.asarray(ref_seq)
  ref_seq = [np.asarray(seq) for seq in ref_seq]
  
  ref_bags = []
  for seq in ref_seq:
    ont_hot_bag = embed(seq, instance_len, instance_stride)
    ref_bags.append(ont_hot_bag)
  
  ref_bags = np.asarray(ref_bags)
  del(ref_seq)
  
  alt_seq = list(SeqIO.parse("/var/www/html/maize/job2/"+jobID+"/download/snp_alt_" + i + "_tmp.fasta", "fasta"))
  alt_seq = str2token(alt_seq)
  # alt_seq = readRDS("/data/zhanmin/maize/traits_web/snp_alt_" + i + "_tmp.rds")
  # alt_seq = np.asarray(alt_seq)
  alt_seq = [np.asarray(seq) for seq in alt_seq]
  alt_bags = []
  for seq in alt_seq:
    ont_hot_bag = embed(seq, instance_len, instance_stride)
    alt_bags.append(ont_hot_bag)
  
  alt_bags = np.asarray(alt_bags)
  del(alt_seq)
  
  ################## predict #######################
  tfk = tf.keras
  tfkl = tf.keras.layers
  tfkc = tf.keras.callbacks
  
  instance_len = 40
  instance_stride = 5
  
  data_name = 'Lung'
  model_name = 'WeakRM'
  
  #data_dir = '/home/zhanmin.liang/traits_web/' 
  target_dir = '/var/www/html/maize/script/'
  checkpoint_filepath = target_dir + model_name + '.h5'
  print('Load weights from:', checkpoint_filepath)
  
  print('loading data')
  # set the label of data
  len_ref =len(ref_bags)
  if i == 'neg':
    itest_label = np.zeros((len_ref, 0))
  elif i == 'pos':
    itest_label = np.zeros((len_ref, 1))
  
  ref_dataset = tf.data.Dataset.from_generator(lambda: itertools.zip_longest(ref_bags, itest_label),
                                                 output_types=(tf.float32, tf.int32),
                                                 output_shapes=(tf.TensorShape([None, instance_len, 4]),
                                                                tf.TensorShape([None])))
  ref_dataset = ref_dataset.batch(1)
  
  alt_dataset = tf.data.Dataset.from_generator(lambda: itertools.zip_longest(alt_bags, itest_label),
                                                 output_types=(tf.float32, tf.int32),
                                                 output_shapes=(tf.TensorShape([None, instance_len, 4]),
                                                                tf.TensorShape([None])))
  alt_dataset = alt_dataset.batch(1)
  
  print('creating model')
  if isinstance(model_name, str):
      dispatcher = {'WeakRM': WeakRM,
                    'WeakRMLSTM': WeakRMLSTM}
      try:
          model_funname = dispatcher[model_name]
      except KeyError:
          raise ValueError('invalid input')
  
  model = model_funname()
  
  # get the probability of ref seq
  model(ref_bags[0].reshape(1, -1, instance_len, 4).astype(np.float32))
  model.load_weights(checkpoint_filepath)
  model.summary()
  
  ref_predictions = []
  for tdata in ref_dataset:
      pred, _ = model(tdata[0], training=False)
      ref_predictions.append(pred.numpy())
  
  ref_predictions = np.concatenate(ref_predictions, axis=0)
  
  # get the probability of alt seq
  model = model_funname()
  model(alt_bags[0].reshape(1, -1, instance_len, 4).astype(np.float32))
  model.load_weights(checkpoint_filepath)
  model.summary()
  
  alt_predictions = []
  for tdata in alt_dataset:
      pred, _ = model(tdata[0], training=False)
      alt_predictions.append(pred.numpy())
  
  alt_predictions = np.concatenate(alt_predictions, axis=0)
  
  ref_index = np.arange(0, len(ref_predictions))
  alt_index = np.arange(0, len(alt_predictions))
  
  if i == 'neg': 
    if list(ref_predictions[ref_predictions > 0.5]) is None:
      print('There has not m6A modification for reference sequences.\n')
    if list(alt_predictions[alt_predictions > 0.5]) is None:
      print('There has not m6A modification for alternative sequences.\n')
    
    loss = ref_predictions - alt_predictions
    gain = alt_predictions - ref_predictions
    # Loss
    p_index = np.where(ref_predictions > 0.7)[0]
    l_index = np.where(loss >= 0.2)[0]
    loss_index = np.intersect1d(p_index, l_index)
    # Gain
    n_index = np.where(ref_predictions <= 0.7)[0]
    g_index = np.where(gain >= 0.2)[0]
    gain_index = np.intersect1d(n_index, g_index) 
    print(gain_index)
    print(loss_index)
    save = pd.DataFrame(loss_index+1)
    save.to_csv('/var/www/html/maize/job2/'+jobID+'/download/traits_unlabel_loss.csv', index = False, header = False)
    save = pd.DataFrame(gain_index+1)
    save.to_csv('/var/www/html/maize/job2/'+jobID+'/download/traits_unlabel_gain.csv', index = False, header = False)
  
  elif i == 'pos': #Loss
    pre = ref_predictions
    pre[alt_predictions >= 0.5] =  1
    pre[alt_predictions < 0.5] = 0
    mutation = np.where(pre != itest_label)
    print(mutation)
    save = pd.DataFrame(mutation[0]+1)
    save.to_csv('/var/www/html/maize/job2/'+jobID+'/download/traits_pos_loss.csv', index = False, header = False)
    
  else:
    print("error")
  
  np.save('traits_'+ i +'_label.npy', itest_label)
  del((ref_bags, alt_bags, ref_dataset, alt_dataset, model))
  
print('#################### start finding mutation sites ############################')
os.system("mkdir /var/www/html/maize/job2/"+jobID+"/download/Results/")
rscript=('/usr/bin/Rscript /var/www/html/maize/traits_script/find_mut_site.R'+' '+jobID)
p=os.system(rscript)

fileStatusjson = [jobID, "Process Complated"]
fileStatusname = "/var/www/html/maize/job2/"+jobID+"/"+jobID+"_upload.json"
json_str = json.dumps(fileStatusjson)
with open(fileStatusname, 'w') as json_file:
    json_file.write(json_str)

os.chdir("/var/www/html/maize/job2/"+jobID+"/download/")
os.system("rm *_tmp.rds")
os.system("rm *_tmp.fasta")
os.system("rm traits_*_label.npy")
