const app = new Vue({
    el: '#app',

    data() {
        return{
            data:{
                type:'',
                db:[]
            },    
            responseURL:null,
            links:[]
        }
    },
    methods:{

        download(){
            this.links = []

            
            if(this.data.db.length>1){

                for (let i = 0; i < this.data.db.length; i++) {                    
                    
                    var link = `${window.location.href}download?type=${this.data.type}&db[]=${this.data.db[i]}`
                    this.links.push(link)
                }

            }

            axios.get(`/download`, {params: {type: this.data.type, db: this.data.db,}
                }).then(resp=>{
                    this.responseURL  = resp.request.responseURL
                    window.location.href = this.responseURL;

                }) .catch(function (error) {
                    console.log(error);
                })

        }
    }
});
