$(document).ready(function () {
    var image = $('img');

    image.mapster(
    {
         fillOpacity: 0.4,
         fillColor: "EFF353",
         strokeColor: "EFF353",
         strokeOpacity: 1,
         strokeWidth: 4,
         stroke: true,
         isSelectable: false,
         singleSelect: true,
         mapKey: 'name',
         listKey: 'key',
         showToolTip: true,
         toolTipClose: ["area-mouseout"],
         // toolTipContainer: '<div></div>',
         areas: [
            // tissue
            {
                key: "lung",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to lung</b><br>TCGA-LUAD: 37,027 <br>TCGA-LUSC: 33,554<br>TCGA-MESO: 656</span>",
                includeKeys: 'lung2'

            },
            {
                key: "lung2",
                includeKeys: 'lung',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to lung</b><br>TCGA-LUAD: 37,027 <br>TCGA-LUSC: 33,554<br>TCGA-MESO: 656</span>"
            },

            // tissue
            {
                key: "bladder",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to bladder</b><br>TCGA-BLCA: 33,999",
                includeKeys: 'bladder2'

            },
            {
                key: "bladder2",
                includeKeys: 'bladder',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to bladder</b><br>TCGA-BLCA: 33,999"
            },

            // tissue
            {
                key: "colon",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to colon</b><br>TCGA-COAD: 47,377",
                includeKeys: 'colon2'

            },
            {
                key: "colon2",
                includeKeys: 'colon',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to colon</b><br>TCGA-COAD: 47,377"
            },

            // tissue
            {
                key: "Lymphocyte",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to lymph nodes</b><br>TCGA-DLBC: 1,416",
                includeKeys: 'Lymphocyte2'

            },
            {
                key: "Lymphocyte2",
                includeKeys: 'Lymphocyte',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to lymph nodes</b><br>TCGA-DLBC: 1,416"
            },

            // tissue
            {
                key: "brain",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to brain</b><br>TCGA-GBM (Cerebrum): 14,850<br>TCGA-GBM (Cerebellum): 15,219<br>TCGA-GBM (Hypothalamus): 13,186<br>TCGA-GBM (Brainstem): 13,600<br><br>TCGA-LGG (Cerebrum): 8,248<br>TCGA-LGG (Cerebellum): 8,471<br>TCGA-LGG (Hypothalamus): 6,796<br>TCGA-LGG (Brainstem): 7,395",
                includeKeys: 'brain2'

            },
            {
                key: "brain2",
                includeKeys: 'brain',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to brain</b><br>TCGA-GBM (Cerebrum): 14,850<br>TCGA-GBM (Cerebellum): 15,219<br>TCGA-GBM (Hypothalamus): 13,186<br>TCGA-GBM (Brainstem): 13,600<br><br>TCGA-LGG (Cerebrum): 8,248<br>TCGA-LGG (Cerebellum): 8,471<br>TCGA-LGG (Hypothalamus): 6,796<br>TCGA-LGG (Brainstem): 7,395"
            },

            // tissue
            {
                key: "kidney",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to kidney</b><br>TCGA-KIRC: 4,162<br>TCGA-KICH: 502<br>TCGA-KIRP: 4,163",
                includeKeys: 'kidney2'

            },
            {
                key: "kidney2",
                includeKeys: 'kidney',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to kidney</b><br>TCGA-KIRC: 4,162<br>TCGA-KICH: 502<br>TCGA-KIRP: 4,163"
            },

            // tissue
            {
                key: "Hematopoietic",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to bone marrow</b><br>TCGA-LAML: 763",
                includeKeys: 'Hematopoietic2'

            },
            {
                key: "Hematopoietic2",
                includeKeys: 'Hematopoietic',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to bone marrow</b><br>TCGA-LAML: 763"
            },

            // tissue
            {
                key: "liver",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to liver</b><br>TCGA-LIHC: 11,716<br>TCGA-CHOL: 898",
                includeKeys: 'liver2'

            },
            {
                key: "liver2",
                includeKeys: 'liver',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to liver</b><br>TCGA-LIHC: 11,716<br>TCGA-CHOL: 898"
            },

            // tissue
            {
                key: "ovary",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to ovary</b><br>TCGA-OV: 9,999",
                includeKeys: 'ovary2'

            },
            {
                key: "ovary2",
                includeKeys: 'ovary',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to ovary</b><br>TCGA-OV: 9,999"
            },

            // tissue
            {
                key: "prostate",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to prostate</b><br>TCGA-PRAD: 5,307",
                includeKeys: 'prostate2'

            },
            {
                key: "prostate2",
                includeKeys: 'prostate',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to prostate</b><br>TCGA-PRAD: 5,307"
            },

            // tissue
            {
                key: "soft tissue",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to soft tissue</b><br>TCGA-SARC: 5,321",
                includeKeys: 'soft tissue2'

            },
            {
                key: "soft tissue2",
                includeKeys: 'soft tissue',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to soft tissue</b><br>TCGA-SARC: 5,321"
            },

            // tissue
            {
                key: "skin",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to skin</b><br>TCGA-SKCM: 104,798",
                includeKeys: 'skin2'

            },
            {
                key: "skin2",
                includeKeys: 'skin',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to skin</b><br>TCGA-SKCM: 104,798"
            },

            // tissue
            {
                key: "stomach",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to stomach</b><br>TCGA-STAD: 42,117",
                includeKeys: 'stomach2'

            },
            {
                key: "stomach2",
                includeKeys: 'stomach',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to stomach</b><br>TCGA-STAD: 42,117"
            },

            // tissue
            {
                key: "uterus",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to uterus</b><br>TCGA-UCEC: 128,883",
                includeKeys: 'uterus2'

            },
            {
                key: "uterus2",
                includeKeys: 'uterus',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to uterus</b><br>TCGA-UCEC: 128,883"
            },

            // tissue
            {
                key: "adrenal",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to adrenal gland</b><br>TCGA-ACC: 2,694<br>TCGA-PCPG: 403",
                includeKeys: 'adrenal2'

            },
            {
                key: "adrenal2",
                includeKeys: 'adrenal',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to adrenal gland</b><br>TCGA-ACC: 2,694<br>TCGA-PCPG: 403"
            },

            // tissue
            {
                key: "rectum",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to rectum</b><br>TCGA-READ: 13,635",
                includeKeys: 'rectum2'

            },
            {
                key: "rectum2",
                includeKeys: 'rectum',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to rectum</b><br>TCGA-READ: 13,635"
            },

            // tissue
            {
                key: "heart",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to heart</b><br>TCGA-THYM: 609",
                includeKeys: 'heart2'

            },
            {
                key: "heart2",
                includeKeys: 'heart',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to heart</b><br>TCGA-THYM: 609"
            },

            // tissue
            {
                key: "testis",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to testis</b><br>TCGA-TGCT: 517",
                includeKeys: 'testis2'

            },
            {
                key: "testis2",
                includeKeys: 'testis',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to testis</b><br>TCGA-TGCT: 517"
            },

            // tissue
            {
                key: "thyroid",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to thyroid</b><br>TCGA-THCA: 1,154",
                includeKeys: 'thyroid2'

            },
            {
                key: "thyroid2",
                includeKeys: 'thyroid',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to thyroid</b><br>TCGA-THCA: 1,154"
            },

            // tissue
            {
                key: "pancreas",
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to pancreas</b><br>TCGA-PAAD: 7,767",
                includeKeys: 'pancreas2'

            },
            {
                key: "pancreas2",
                includeKeys: 'pancreas',
                strokeColor: "d42e16",
                fillColor: "d42e16",
                fillOpacity: 0.2,
                strokeWidth: 2,
                toolTip: "<span style='font-size:17px'><b>Somatic m6A-associated variants corresponding to pancreas</b><br>TCGA-PAAD: 7,767"
            },
          ]

     });
   });
