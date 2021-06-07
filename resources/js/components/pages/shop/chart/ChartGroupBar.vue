<template>
<div class="content-wrapper">
<section class="content">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Chart Bar</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/chart/group/bar">Home</router-link></li>
              <li class="breadcrumb-item active">Chart</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div>
    </section><!-- /.container-fluid -->
        <div id="page-wrapper">
          <div class="form-group row float-left" style="margin-left : 40px" >
            <label  class=" col-form-label " style="width: 100px">Select Year</label>
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
          </div>
          <!-- <div class="form-group row float-right" style="margin-right : 15px" >
            <button class="btn btn-success" @click.prevent="rewardPost()">Reward</button>
          </div> -->
          <br>

            
            <div class="container-fluid">
                 <chart :height="170"  :chart-data="datacollection"></chart>
             
            </div>  
        </div>
    </section>
</div>
</template>

<script>
  import Chart from "../model/Bar.vue";
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
          getid: {
            id: localStorage.getItem('id')
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
        // rewardPost(){
        //     let formData = new FormData();
        //     let i;
        //     for( i = 0; i < this.id.length; i ++){
        //       formData.append("id["+i+"]",this.id[i]);
        //       console.log(this.id[i]);
        //     }
            
        //     let uri = `https://damp-depths-89624.herokuapp.com/api/post/rewardcoments`;
        //     this.axios.post(uri, formData, {
        //           headers: {
        //             'Content-Type': 'multipart/form-data'
        //           }
        //     }).then((response) => {
        //        if(response.status == 200){
        //                 this.$toast.open({
        //                     message: response.data,
        //                     type: "success",
        //                     duration: 1000,
        //                     dismissible: true
        //                 })
        //             }else{
        //                  this.$toast.open({
        //                     message: response.data,
        //                     type: "error",
        //                     duration: 1000,
        //                     dismissible: true
        //                 })
        //             }
        //     });
        // },
        fillData() {
        let uri = `http://127.0.0.1:8000/api/shop/chartLine/groupbar/${this.thisyear}`;
          let labels = new Array();
         let Count = new Array();
         this.axios.post(uri,this.getid).then((response) => {
            let datas =response.data ;                
             

            
            if(datas) {
                datas.forEach(element => {
                  // this.id.push(element.id);
                  labels.push(element.name);
                  Count.push(element.amount);
                });
             console.log(localStorage.getItem('id'));
             console.log(response.data);
                this.datacollection = 
                 {
                  labels:labels,
                  datasets: [{
                    label: "Totals Rating",
                    backgroundColor: "rgba(255, 0, 0, 0.25)",
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