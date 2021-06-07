<template>
<div class="content-wrapper">
     <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/shop/home">Home</router-link></li>
              <li class="breadcrumb-item active">Add Product</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">From Create</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="quickForm" @submit.prevent="addImages">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name product</label>
                    <input type="text" class="form-control" v-model="add.nameproduct" id="exampleInputEmail1" placeholder="Enter name of product">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Price Product</label>
                    <input type="text"  class="form-control" v-model="add.priceproduct"  id="exampleInputPassword1" placeholder="Enter price of product (vd: 200000) vnd">
                  </div>
                   <div class="form-group">
                    <label for="exampleFormControlTextarea1">Description</label>
                    <textarea class="form-control" v-model="add.description" id="exampleFormControlTextarea1" placeholder="Describe yourself here..." rows="3"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Amount Product</label>
                    <input type="text"  class="form-control" v-model="add.amountproduct"  id="exampleInputPassword1" placeholder="Enter amount product">
                  </div>
                   <div class="form-group">
                    <label for="exampleInputPassword1">Discount Product</label>
                    <input type="text"  class="form-control" v-model="add.discountproduct"  id="exampleInputPassword1" placeholder="VD: 10, 20 (%)">
                  </div>
                  <div class="form-group colum">
                    <label for="example-date-input" class="col-2 col-form-label">Date</label>
                    <div class="col-10">
                      <input class="form-control" v-model="add.date" style="margin-left:-10px ; width: 30%" type="date" value="2021-05-10" id="example-date-input">
                    </div>
                  </div>
                    <div class="form-group">
                    <label>Minimal</label>
                    <select class="form-control select2" style="width: 100%;" v-model="add.type">
                        <option  v-for="item in this.group" :key="item.id" :value="item.id">{{item.name}}</option>                    
                    </select>
                    </div>
                    <div class="form-group">
                    <label for="exampleInputEmail1">Choose images</label>
                    <div id="app" >
                            <input   type="file" class="form-control" multiple accept="image/jpeg/jpg" @change="uploadImage"/>
                            <div style="display: inline; margin-top: 30px; margin-right: 20px" v-for="(data, key) in datas.url" :key="key">
                                <img :src="data" class="preview" width="60px" height="60px" />
                            </div>
                    </div> 
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
   
            </div>
            <div class="col-md-6">
          </div>
        </div>
      </div>
    </section>
</div>
</template>
<script>
    export default {
        data(){
        return {
          group:{},
          images: [],
          add: {
            type: 1,
          },
          datta: [],
          datas: {
               file: [],
               url: [],
           },
           
        }
    },
    created() {
        this.refreshData();
        console.log(this.group);
    },
    methods: {
      refreshData() {
            let uri = "http://127.0.0.1:8000/api/shop/getgroup";
            this.axios.get(uri).then(response => {
                this.group = response.data;
            });
            console.log(this.group);
        },
        addImages(){
            const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
            let formData = new FormData(); 
            for (var x  = 0; x < this.datas.file.length; x++) {
                formData.append("file["+x+"]", this.datas.file[x]);
            }
            formData.append('nameproduct',this.add.nameproduct);
            formData.append('priceproduct',this.add.priceproduct);
            formData.append('description',this.add.description);
            formData.append('amountproduct',this.add.amountproduct);
            formData.append('discountproduct',this.add.discountproduct);
            formData.append('date',this.add.date);
            formData.append('type',this.add.type);

            let uri = `http://127.0.0.1:8000/api/shop/post/create/${localStorage.getItem('id')}`;
            this.axios.post(uri,formData,config).then(response => {
            // this.axios.post(uri,this.add).then(response => {
                if(response.status == 200){
                    this.$toast.open({
                        message: response.data,
                        type: "success",
                        duration: 1000,
                        dismissible: true
                    });
                    this.$router.push({path: '/shop/home'});
                }  
            });
        },
        uploadImage(e) {
            var selectedFiles = e.target.files;
            let  src ;
            let file;
            for (let i = 0; i < selectedFiles.length; i++) {
                    src = URL.createObjectURL(selectedFiles[i]);
                    file = selectedFiles[i];
                this.datas.file.push(file);
                this.datas.url.push(src);
                // console.log(this.datas.url[i]);
            }
        },

    
    }
  }
</script>