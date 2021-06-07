<template>
<div class="content-wrapper">
     <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Update Avatar</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/shop/account">Home</router-link></li>
              <li class="breadcrumb-item active">Update Avatar</li>
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
          datas: {
               file: [],
               url: [],
           },
           
        }
    },
    methods: {
        addImages(){
            const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
            let formData = new FormData(); 
                formData.append("file", this.datas.file[0]);
            let uri = `http://127.0.0.1:8000/api/shop/post/updateAvatar/${localStorage.getItem('id')}`;
            this.axios.post(uri,formData,config).then(response => {
                if(response.status === 200){
                    this.$toast.open({
                        message: response.data,
                        type: "success",
                        duration: 1000,
                        dismissible: true
                    });
                    this.$router.push({path: '/shop/account'});
                }  
            });
        },
        uploadImage(e) {
            var selectedFiles = e.target.files;
            this.datas.file.push(selectedFiles[0]);
            this.datas.url.push(URL.createObjectURL(selectedFiles[0]));
        },

    
    }
  }
</script>