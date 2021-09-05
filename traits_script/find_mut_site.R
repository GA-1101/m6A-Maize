library(jsonlite)
library(Biostrings)
#library(GenomicFeatures)
library(GenomicRanges)
library(readr)
library(ChIPseeker)
library(GenomicFeatures)
specie <- 'maize_'
m_traits <- readRDS("/var/www/html/maize/traits_script/gwas_traits.rds")
maize_txdb <- loadDb("/var/www/html/maize/traits_script/maize_v4.sqlite")

input_json <- commandArgs(trailingOnly = T)
jobID <- input_json[1]

# read multi snp data  (chr, site, ref, alt)
tryCatch(
  {loss <- read.csv(paste0('/var/www/html/maize/job2/',jobID,'/download/traits_pos_loss.csv'), header = F)
  pos_data <- readRDS(paste0("/var/www/html/maize/job2/",jobID,"/download/snp_pos_data_tmp.rds"))
  for (i in loss) {pos_traits <- pos_data[i]}
  post_lap <- findOverlaps(pos_traits, m_traits)
  pos_traits$trait <- m_traits[subjectHits(post_lap)]$trait
  
  peakAnno <- annotatePeak(pos_traits, overlap = "all",
                           TxDb=maize_txdb)
  test <- as.GRanges(peakAnno)
  test$geneId
  plotAnnoPie(peakAnno)
  
  pos_gene <- as.data.frame(peakAnno)
  write.csv(pos_gene, file = paste0('/var/www/html/maize/job2/',jobID,'/download/Results/high-confidence-level_loss.csv'), row.names = TRUE)
  }, 
  error = function(e) { cat("Have not loss mutations for positive data\n",conditionMessage(e),"\n")
    }, 
  finally = {}
)

tryCatch(
  {loss_unlabel <- read.csv(paste0('/var/www/html/maize/job2/',jobID,'/download/traits_unlabel_loss.csv'), header = F)
  neg_data <- readRDS(paste0("/var/www/html/maize/job2/",jobID,"/download/snp_neg_data_tmp.rds"))
  for (i in loss_unlabel) {lossun_traits <- neg_data[i]}
  lossun_lap <- findOverlaps(lossun_traits, m_traits)
  lossun_traits$trait <- m_traits[subjectHits(lossun_lap)]$trait
  
  peakAnno <- annotatePeak(lossun_traits, overlap = "all",
                           TxDb=maize_txdb)
  lossun_gene <- as.data.frame(peakAnno)
  write.csv(lossun_gene, file = paste0('/var/www/html/maize/job2/',jobID,'/download/Results/low-confidence-level_loss.csv'), row.names = TRUE)
  }, 
  error = function(e) { cat("Have not loss mutations for unlabel data\n",conditionMessage(e),"\n")
  }, 
  finally = {}
)

tryCatch(
  {gain_unlabel <- read.csv(paste0('/var/www/html/maize/job2/',jobID,'/download/traits_unlabel_gain.csv'), header = F)
  neg_data <- readRDS(paste0("/var/www/html/maize/job2/",jobID,"/download/snp_neg_data_tmp.rds"))
  for (i in gain_unlabel) {gainun_traits <- neg_data[i]}
  gainun_lap <- findOverlaps(gainun_traits, m_traits)
  gainun_traits$trait <- m_traits[subjectHits(gainun_lap)]$trait
  
  peakAnno <- annotatePeak(gainun_traits, overlap = "all",
                           TxDb=maize_txdb)
  gainun_gene <- as.data.frame(peakAnno)
  write.csv(gainun_gene, file = paste0('/var/www/html/maize/job2/',jobID,'/download/Results/low-confidence-level_gain.csv'), row.names = TRUE)
  }, 
  error = function(e) { cat("Have not gain mutations for unlabel data\n",conditionMessage(e),"\n")
  }, 
  finally = {}
)

