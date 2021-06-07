<template>
<div class="content-wrapper">
     <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Group Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/home">Home</router-link></li>
              <li class="breadcrumb-item active">Add group product</li>
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
                <h3 class="card-title">From Create Group Product</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="quickForm" @submit.prevent="addGroup">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name Group Product</label>
                    <input type="text" class="form-control" v-model="datas.namegroup" id="exampleInputEmail1" placeholder="Enter Username">
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">
                      Add   
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
          datas:{
          },
        }
    },
    methods: {
        addGroup(){
          if(this.datas.namegroup == undefined )
          {
             this.$toast.open({
                message: "Please Fill All data",
                type: "warning",
                duration: 1000,
                dismissible: true
              })
          }else{
              let uri = 'http://127.0.0.1:8000/api/post/addgroup';
              this.axios.post(uri, this.datas).then((response) => {
                  if(response.data == "successfully add group product"){
                    this.$toast.open({
                    message: response.data,
                    type: "success",
                    duration: 2000,
                    dismissible: true
                    })
                  }
                  this.datas.namegroup = "";
              
            //   this.$router.push({path: '/addgroup'});
              
              });
          }
           
        },
    
    }
  }
</script>