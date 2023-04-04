<template>
    <div>
        <h4 class="text-center">Add Book</h4>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="addBook">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" v-model="book.title">
                    </div>
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" class="form-control" v-model="book.author">
                    </div>
                    <div class="form-group">
                        <label>Genre</label>
                        <input type="text" class="form-control" v-model="book.genre">
                    </div>
                    <div class="form-group">
                        <label>Isbn</label>
                        <input type="number" class="form-control" v-model.number="book.isbn">
                    </div>
                    <div class="form-group">
                        <label>Publisher</label>
                        <input type="text" class="form-control" v-model="book.publisher">
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" @change='uploadPhoto' name="photo" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" v-model="book.description"></textarea>
                    </div>

                    
                    <button type="submit" class="btn btn-primary">Add Book</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            book: {}
        }
    },
    methods: {
        uploadPhoto(e){
              let file = e.target.files[0];
                let reader = new FileReader();  

                if(file['size'] < 2111775)
                {
                    reader.onloadend = (file) => {
                     this.book.photo = reader.result;
                    }              
                     reader.readAsDataURL(file);
                }else{
                    alert('File size can not be bigger than 2 MB')
                }
            },
        addBook() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/books/add', this.book)
                    .then(response => {
                        this.$router.push({name: 'books'})
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>