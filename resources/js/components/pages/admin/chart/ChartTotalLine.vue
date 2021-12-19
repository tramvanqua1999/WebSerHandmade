<template>
<div class="content-wrapper">
<section class="content">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Biểu đồ doanh thu qua từng tháng</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/home">Home</router-link></li>
              <li class="breadcrumb-item active">Biểu đồ</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div>
    </section><!-- /.container-fluid -->
        <div id="page-wrapper">
          <div class="form-group row float-left" style="margin-left : 40px" >
            <label  class=" col-form-label " style="width: 100px">Chọn năm</label>
            
            <div>
              <vuejs-datepicker 
              
                :format="DatePickerFormat"
                :language="language"
                minimum-view="year"              
                name="datepicker"
                id="input-id"
                v-model="modelyear"
                input-class="form-control" style="width:70px" @closed="onChange">
              </vuejs-datepicker>
            </div>
          </div><br>

            
            <div class="container-fluid">
                 <chart :height="170"  :chart-data="datacollection"></chart>
             
            </div>  
        </div>
    </section>
</div>
</template>

<script>
  import Chart from "../model/Line.vue";
  import Datepicker from 'vuejs-datepicker';
  export default {
       components: {
        'vuejs-datepicker': Datepicker,
        Chart
      },
      data() {
        return {
           thisyear : '2021',
          modelyear : '2021',
          DatePickerFormat: 'yyyy',
          language:{
            language: 'Cietnamese ', 
            months: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'], 
            monthsAbbr: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'], 
            days: ['Ngày', 'Tháng', 'Năm'], 
            rtl: false, 
            ymd: false, 
            yearSuffix: ' Year',
          },
          datacollection : null,
          backgroundColor : null,
        };
      },
    
      mounted() {
        
        this.fillData();
        
      },
      methods: {
        onChange(){
          this.convert(this.modelyear);
          this.fillData();
        },
         convert(str) {
          var date = new Date(str);
          this.thisyear = date.getFullYear();
        },
        fillData() {
        let uri = `http://127.0.0.1:8000/api/chartLine/totalline/${this.thisyear}`;
          let labels =
          [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
          ];
        
         let Count = new Array();
         this.axios.post(uri).then((response) => {
            let datas = response.data;
            let i;
            let j = 0;
            if(datas) {
               for(i = 1; i < 13 ; i++){
                   if( j < datas.length){
                      if(datas[j].month == i){
                        Count.push(datas[j].total);
                        
                        j ++ ;
                      }else{
                        Count.push(0);
                      }
                    }else{
                        Count.push(0);
                    } 
                };
             
                 
               
              //  for(i = 0; i < 12 ; i++){
              //   console.log('Tháng:' + (i + 1)+' ---- Giá trị là:' + Count[i]);
              //  }
               
           
                this.datacollection = 
                 {
                  labels:labels,
                  datasets: [{
                    label: "Tổng đánh giá",
                 
                    borderColor: "#05CBE1",
                    pointBackgroundColor: "white",
                    pointBorderColor: "white",
                    borderWidth: 1,
                    backgroundColor: "rgba(0, 231, 255, 0.25)",
                    data: Count
                  }]
                };
               
            }
        else {
            console.log('No data');
        }
        }); 
        }
      }
 
}
</script>