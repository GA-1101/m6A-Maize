library(Biostrings)
#library(biomaRt)
#library(GenomicFeatures)
#library(magrittr)
library(GenomicRanges)
library(BSgenome.Zmays.Ensemble.zmv4)
library(jsonlite)
#library(readr)
Zmays <- BSgenome.Zmays.Ensemble.zmv4

input_json <- commandArgs(trailingOnly = T)
jobID <- input_json[1]
a <- as.data.frame(fromJSON(paste0('/var/www/html/maize/job2/',jobID,'/',jobID,'_para.json')))
file <- as.character(a$file)

setwd("/var/www/html/maize/traits_script/")

# read multi snp data  (chr, site, ref, alt)
tryCatch(
  {input <- read.table(file, sep = ',', header = TRUE)
  input_snp <- GRanges(seqnames = as.character(input[,1]),
                        IRanges(start = as.numeric(input[,2]),
                                end = as.numeric(input[,2])),
                        strand = "+")
  input[,3][input[,3] == 'U'] = 'T'
  input[,4][input[,4] == 'U'] = 'T'
  input_snp$alt <- as.character(input[,3])
  input_snp$ref <- as.character(input[,4])
  if ((length(grep("[A|G|C|T|a|g|c|t]", input_snp$ref)) != length(input$ref)) | (length(grep("[A|G|C|T|a|g|c|t]", input_snp$alt)) != length(input$alt))){
    errorCondition("The original or mutated nucleotide should be A/T(U)/C/G.")}
  }, 
  error = function(e) { cat("The data format is incorrect!\n",conditionMessage(e),
                            "\n\nPlease input the chromosome, site of SNP, refrence nucleoitde and alternation nucleotide in order.")
                        q()}, 
  finally = {rm(input)}
)


# str2token <- function(str_sequence) {
#   splitted_seq <- strsplit(str_sequence, '') # split sequence in the data.frame
#   token_seq <- vector(mode='list', length=length(splitted_seq)) # build the empty vector
#   
#   # translation the A,U,C,G into 1,2,3,4
#   for (idx in c(1:length(splitted_seq))){
#     token_seq[idx] = list(as.integer(unlist(splitted_seq[idx]) == 'A') +
#                             2 * as.integer(unlist(splitted_seq[idx]) == 'C') +
#                             3 * as.integer(unlist(splitted_seq[idx]) == 'G') +
#                             4 * as.integer(unlist(splitted_seq[idx]) == 'T'))
#   }
#   return(token_seq)
# }

  

# get the reference and mutated seq with SNP
MutatedSeq <- function(mdtype, snp){
  data <- mdtype #positive.rds
  #data$refseq <- RNAStringSet(DNAStringSet(Views(Zmays,data)))
  
  # find overlap
  overlap <- findOverlaps(snp,data,ignore.strand = TRUE) #find overlaps no matter in forward or reverse strands
  qhits <- queryHits(overlap)
  shits <- subjectHits(overlap)
  
  
  if(length(qhits) != 0){
    PIANO_input <- snp[qhits]
    PIANO_input$m6a_peak_strand <- strand(data)[shits]
    PIANO_input$m6a_peak_range <- ranges(data)[shits]
    PIANO_input$m6a_peak_width <- width(data)[shits]
    PIANO_input$m6a_peak_id <- data$id[shits]
    PIANO_input$reference_sequence <- DNAStringSet(data$refseq[shits])
    rm(data)
    
    #construct mutated seq
    sequence_original <- PIANO_input$reference_sequence
    sequence_mutated <- sequence_original
    snp_starts <- start( PIANO_input )
    range_starts <- start(PIANO_input$m6a_peak_range)
    range_ends <- end(PIANO_input$m6a_peak_range)
    pos_replace_plus <- snp_starts - range_starts + 1
    pos_replace_minus <- range_ends - snp_starts + 1
    nt_replace_plus <- as.character( PIANO_input$alt )
    nt_replace_minus <- as.character( complement( DNAStringSet( PIANO_input$alt ) ) ) #complement: Use these functions for reversing sequences
    indx_minus_range <- as.logical( PIANO_input$m6a_peak_strand == "-" )
    pos_plus_indx <- split(pos_replace_plus,seq_along(pos_replace_plus))[!indx_minus_range]
    names(pos_plus_indx) <- NULL
    sequence_mutated[!indx_minus_range][pos_plus_indx] <- DNAStringSet( nt_replace_plus[!indx_minus_range] )
    pos_minus_indx <- split(pos_replace_minus,seq_along(pos_replace_minus))[indx_minus_range]
    names(pos_minus_indx) <- NULL
    sequence_mutated[indx_minus_range][pos_minus_indx] <- DNAStringSet( nt_replace_minus[indx_minus_range] )
    
    PIANO_input$alterative_sequence <- sequence_mutated
    PIANO_input$snp_pos <- pos_replace_plus
    PIANO_input$snp_pos[indx_minus_range] <- pos_replace_minus[indx_minus_range]
    rm(overlap,pos_replace_plus,nt_replace_minus,pos_minus_indx,pos_plus_indx,sequence_mutated,sequence_original,
       indx_minus_range,nt_replace_plus,pos_replace_minus,qhits,shits,range_ends,range_starts,snp_starts)
  }else{
    PIANO_input <- data[qhits]
    rm(qhits, shits, overlap, data)
  }
  
  return(PIANO_input)
}


tryCatch(
  {positive <- readRDS("/var/www/html/maize/traits_script/pos_data_web.rds")
  pos_data<- MutatedSeq(positive, input_snp)
  if (!is.null(pos_data)){
    pos_ref <- pos_data$reference_sequence
    pos_alt <- pos_data$alterative_sequence
    # pos_ref <- as.character(pos_data$reference_sequence)
    # pos_alt <- as.character(pos_data$alterative_sequence)
    # pos_ref <- str2token(pos_ref)
    # pos_alt <- str2token(pos_alt)
    writeXStringSet(pos_ref, paste0("/var/www/html/maize/job2/",jobID,"/download/snp_ref_pos_tmp.fasta"))
    writeXStringSet(pos_alt, paste0("/var/www/html/maize/job2/",jobID,"/download/snp_alt_pos_tmp.fasta"))
    # saveRDS(pos_ref, "snp_ref_pos_tmp.rds")
    # saveRDS(pos_alt, "snp_alt_pos_tmp.rds")
    saveRDS(pos_data, paste0("/var/www/html/maize/job2/",jobID,"/download/snp_pos_data_tmp.rds"))
  }
  
  negative <- readRDS("/var/www/html/maize/traits_script/neg_data_web.rds")
  neg_data <- MutatedSeq(negative, input_snp)
  if (!is.null(neg_data)){
    neg_ref <- neg_data$reference_sequence
    neg_alt <- neg_data$alterative_sequence
    # neg_ref <- as.character(neg_data$reference_sequence)
    # neg_alt <- as.character(neg_data$alterative_sequence)
    # neg_ref <- str2token(neg_ref)
    # neg_alt <- str2token(neg_alt)
    writeXStringSet(neg_ref, paste0("/var/www/html/maize/job2/",jobID,"/download/snp_ref_neg_tmp.fasta"))
    writeXStringSet(neg_alt, paste0("/var/www/html/maize/job2/",jobID,"/download/snp_alt_neg_tmp.fasta"))
    # saveRDS(neg_ref, "snp_ref_neg_tmp.rds")
    # saveRDS(neg_alt, "snp_alt_neg_tmp.rds")
    saveRDS(neg_data, paste0("/var/www/html/maize/job2/",jobID,"/download/snp_neg_data_tmp.rds"))
  }}, 
  error = function(e) { cat("The data format is incorrect!\n",conditionMessage(e),
                            "\n\nThe mutated nucleotides should be A/C/G/T.")}, 
  finally = {}
)

if (is.null(pos_data) & is.null(neg_data)){
  cat("There has not related m6A modified site for this SNP site.")
}

