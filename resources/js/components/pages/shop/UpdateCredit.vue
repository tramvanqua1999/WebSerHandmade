<template>
<div class="content-wrapper">
     <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cập nhật thẻ VNPAY</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/shop/account">Home</router-link></li>
              <li class="breadcrumb-item active">Update Credit</li>
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
                <h3 class="card-title">Bảng cập nhật thẻ VNPAY</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="quickForm" @submit.prevent="update">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Tên chủ thẻ</label>
                    <input type="text" class="form-control" @input="datas[0].cardHolderName = $event.target.value" id="exampleInputEmail1" :value="datas[0].cardHolderName">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Số PIN</label>
                     <textarea class="form-control" @input="datas[0].cardNumber = $event.target.value" id="exampleFormControlTextarea1" :value="datas[0].cardNumber" rows="3"></textarea>
                  </div>
                   <div class="form-group">
                    <label for="exampleFormControlTextarea1">Ngày hết hạn</label>
                   <input type="text"  class="form-control"  @input="datas[0].expiryDate = $event.target.value" id="exampleInputPassword1" :value="datas[0].expiryDate">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">CvvCode</label>
                    <input type="text"  class="form-control" @input="datas[0].cvvCode = $event.target.value" id="exampleInputPassword1" :value="datas[0].cvvCode">
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Cập nhật thẻ</button>
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
        }
    },
    created() {
        this.refreshData();
        console.log(this.group);
    },
    methods: {
      refreshData() {
            let uri = `http://127.0.0.1:8000/api/shop/post/getinfoShop/${localStorage.getItem('id')}`;
            this.axios.post(uri).then(response => {
                this.datas = response.data;
            });
        },
        update(){
          let uri = `http://127.0.0.1:8000/api/shop/post/updatecreditShop/${localStorage.getItem('id')}`;
          // console.log(localStorage.getItem('id'));
          console.log(this.datas[0]);
             this.axios.post(uri,this.datas[0]).then(response => {
                if(response.status === 200){
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