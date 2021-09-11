const app = new Vue({
    el: '#app',

    data() {
        return{
            data:{
                type:'',
                db:[]
            },    
            responseURL:null,
            loader:false
        }
    },
    methods:{

        download(){
            this.loader = true

            axios.get(`/download`, {params: {type: this.data.type, db: this.data.db,}
                }).then(resp=>{
                    this.responseURL  = resp.request.responseURL

                    window.location.href = this.responseURL;

                    this.loader = false

                }) .catch(function (error) {
                    console.log(error);
                })

        }
    }
});
