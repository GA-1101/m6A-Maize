import os
import sys
import json
import numpy as np
from rpy2.rinterface_lib import openrlib
from utils import embed
import itertools
from sklearn.metrics import accuracy_score, f1_score, precision_score, recall_score, matthews_corrcoef
from sklearn.metrics import roc_auc_score, average_precision_score
import tensorflow as tf
from nets import WSCNN, WSCNNLSTM, WeakRM, WeakRMLSTM
from prettytable import PrettyTable
import time

jobID = sys.argv[1]

rscript=('/usr/bin/Rscript /var/www/html/maize/script/full_script_web.R'+' '+jobID)
os.system(rscript)

with openrlib.rlock:
    import rpy2.robjects as ro
    readRDS = ro.r['readRDS']
    pass

np.random.seed(323)



data_dir = './'
target_dir = data_dir + 'npydata/'
specie = 'maize_'
#if not os.path.exists(target_dir):
#  os.mkdir(target_dir)

instance_len = 40
instance_stride = 5

test_seq = readRDS("/var/www/html/maize/job/"+jobID+"/download/input_seq.rds")
test_seq = np.asarray(test_seq)
test_seq = [np.asarray(seq) for seq in test_seq]

test_bags = []
for seq in test_seq:
  ont_hot_bag = embed(seq, instance_len, instance_stride)
  test_bags.append(ont_hot_bag)

test_bags = np.asarray(test_bags)
del(test_seq)

################## predict #######################
tfk = tf.keras
tfkl = tf.keras.layers
tfkc = tf.keras.callbacks

instance_len = 40
instance_stride = 5

data_name = 'Lung'
model_name = 'WeakRM'


checkpoint_filepath = '/var/www/html/maize/script/'+ model_name + '.h5'
print('Load weights from:', checkpoint_filepath)

print('loading data')
itest_data = test_bags
length =len(itest_data)
itest_label = np.zeros((length, 1))
del(test_bags)
itest_dataset = tf.data.Dataset.from_generator(lambda: itertools.zip_longest(itest_data, itest_label),
                                               output_types=(tf.float32, tf.int32),
                                               output_shapes=(tf.TensorShape([None, instance_len, 4]),
                                                              tf.TensorShape([None])))
itest_dataset = itest_dataset.batch(1)

print('creating model')
if isinstance(model_name, str):
    dispatcher = {'WeakRM': WeakRM,
                  'WeakRMLSTM': WeakRMLSTM}
    try:
        model_funname = dispatcher[model_name]
    except KeyError:
        raise ValueError('invalid input')

model = model_funname()

model(itest_data[0].reshape(1, -1, instance_len, 4).astype(np.float32))
model.load_weights(checkpoint_filepath)
model.summary()

predictions = []
for tdata in itest_dataset:
    pred, _ = model(tdata[0], training=False)
    predictions.append(pred.numpy())

print("The result of prediction:")
predictions = np.concatenate(predictions, axis=0)
np.set_printoptions(suppress=True)
np.set_printoptions(precision=4)
np.savetxt('/var/www/html/maize/job/'+jobID+'/download/Results/output.txt', predictions, fmt = '%.04f')
print(predictions)

fileStatusjson = [jobID, "Process Complated"]
fileStatusname = "/var/www/html/maize/job/"+jobID+"/"+jobID+"_upload.json"
json_str = json.dumps(fileStatusjson)
with open(fileStatusname, 'w') as json_file:
    json_file.write(json_str)

os.chdir("/var/www/html/maize/job/"+jobID+"/download/")
os.system("rm input_seq.rds")
