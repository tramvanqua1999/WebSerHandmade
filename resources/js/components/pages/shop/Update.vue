<template>
<div class="content-wrapper">
     <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cập nhật sản phẩm</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/shop/home">Home</router-link></li>
              <li class="breadcrumb-item active">Cập nhật sản phẩm</li>
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
                <h3 class="card-title">Bảng cập nhật sản phẩm</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="quickForm" @submit.prevent="updateproduct" >
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Tên sản phẩm</label>
                    <input type="text" class="form-control" @input="datas[0].name = $event.target.value"  id="exampleInputEmail1" :value="datas[0].name">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Giá</label>
                    <input type="text"  class="form-control"   @input="datas[0].price = $event.target.value" id="exampleInputPassword1" :value="datas[0].price">
                  </div>
                   <div class="form-group">
                    <label for="exampleFormControlTextarea1">Mô tả</label>
                    <textarea class="form-control" @input="datas[0].description = $event.target.value" id="exampleFormControlTextarea1" :value="datas[0].description" rows="3"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Số lượng</label>
                    <input type="text"  class="form-control"  @input="datas[0].amount = $event.target.value" id="exampleInputPassword1" :value="datas[0].amount">
                  </div>
                   <div class="form-group">
                    <label for="exampleInputPassword1">Giảm giá</label>
                    <input type="text"  class="form-control" @input="datas[0].discount = $event.target.value"  id="exampleInputPassword1" :value="datas[0].discount">
                  </div>
                  <div class="form-group colum">
                    <label for="example-date-input" class="col-2 col-form-label">Ngày</label>
                    <div class="col-10">
                      <input class="form-control" @input="datas[0].lastday = $event.target.value" style="margin-left:-10px ; width: 30%" type="date" :value="datas[0].lastday" id="example-date-input">
                    </div>
                  </div>
                    <div class="form-group">
                    <label>Nhóm</label>
                    <select class="form-control select2" style="width: 100%;" v-model="datas[0].groupProduct" >
                        <option  v-for="item in this.group" :key="item.id" :value="item.id">{{item.name}}</option>                    
                    </select>
                    </div>
                    <div class="form-group">
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
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
          datas: [],
          add: [],
          group: [],
        }
    },
    created() {
        this.refreshData();
        this.refreshDatagroup();
        
    },
    methods: {
      refreshData() {
            let uri = `http://127.0.0.1:8000/api/shop/post/getinfupdate/${localStorage.getItem('temp')}`;
            this.axios.post(uri).then(response => {
                this.datas = response.data;
            });
        },
        refreshDatagroup() {
           let uri = "http://127.0.0.1:8000/api/shop/getgroup";
            this.axios.get(uri).then(response => {
                this.group = response.data;
            });
        },
        updateproduct(){
            let uri = `http://127.0.0.1:8000/api/shop/post/update/${localStorage.getItem('temp')}`;
            this.axios.post(uri,this.datas[0]).then(response => {
                if(response.data =="successfully update"){
                    this.$toast.open({
                    message: response.data,
                    type: "success",
                    duration: 1000,
                    dismissible: true
                });
                 this.$router.push({
                    path: "/shop/account",})
                }
                
            });
        },
    }
  }
</script>