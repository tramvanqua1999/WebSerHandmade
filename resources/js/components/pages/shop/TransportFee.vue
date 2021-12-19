<template>
<div class="content-wrapper">
     <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Thêm phí vận chuyển</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/shop/home">Home</router-link></li>
              <li class="breadcrumb-item active">Thêm phí vận chuyển</li>
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
            <div class="card card-primary" style="width: 70%; margin: 0 auto">
              <div class="card-header">
                <h3 class="card-title">Bảng thêm phí vận chuyển</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="quickForm" @submit.prevent="addFee">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Số tiền tối thiểu để được giao hàng miễn phí</label>
                    <input type="text" class="form-control" @input="datas[0].fee = $event.target.value" id="exampleInputEmail1" :value="datas[0].fee">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nếu không: Phí vận chuyển</label>
                    <input type="text" class="form-control" @input="datas[0].price = $event.target.value" id="exampleInputEmail1" :value="datas[0].price">
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">
                     Thêm phí vận chuyển
                  </button>
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
          datas:[]
        }
    },
    created() {
        this.refreshData();
    },
    methods: {
         refreshData() {
            let uri = `http://127.0.0.1:8000/api/shop/post/getfee/${localStorage.getItem('id')}`;
            this.axios.post(uri).then(response => {
                this.datas = response.data;
            });
        },
        addFee(){
            console.log(localStorage.getItem('id'));
            console.log(this.datas[0].fee);
            console.log(this.datas[0].price);
              let uri = `http://127.0.0.1:8000/api/shop/post/addfee/${localStorage.getItem('id')}`;
              this.axios.post(uri, this.datas[0]).then((response) => {
                  if(response.data == "successfully update"){
                    this.$toast.open({
                    message: response.data,
                    type: "success",
                    duration: 2000,
                    dismissible: true
                    })
                    this.$router.push({ 
                    path: "/shop/home",})
                  }
              });
          }  
        },
  }
</script>