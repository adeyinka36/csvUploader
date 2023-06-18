<template>
    <div id="con">
        <h1>Please Upload a CSV file</h1>
        <form @submit.prevent="onSubmit">
            <label for="file-upload" class="custom-file-upload">
                <input id="file-upload" ref="upload" type="file" @change="handleFileUpload" />
            </label>
            <button type="submit">{{ buttonLabel }}</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    data() {
        return {
            selectedFile: null,
        };
    },
    computed: {
        buttonLabel() {
            return this.selectedFile ? `Upload ${this.selectedFile.name}` : 'No file chosen';
        },
    },
    methods: {
        handleFileUpload(event) {
            this.selectedFile = event.target.files[0];
        },
        async onSubmit() {
            if (!this.selectedFile) return;
            const formData = new FormData();
            formData.append('file', this.selectedFile);
            try {
                this.$emit('isLoading', true);
                await axios.post('/api/v1/upload/persons', formData);
                this.selectedFile = null;
                this.$refs.upload.value = null;
                this.$emit('isLoading', false);
                this.$emit('notification', 'File successfully uploaded!');
            } catch (err) {
                this.$emit('isLoading', false);
                this.$refs.upload.value = null;
                this.selectedFile = null;
                this.$emit('notification', 'An error occurred while uploading the file');
            }
        },
    },
};
</script>

<style scoped>
#con {
    height: 100vh;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;

}
#con h1{
    color: white;
    margin-bottom: .5rem;
    text-align: center;
    font-weight: bold;
}

form {
    text-align: center;
}

.custom-file-upload {
    border: 1px solid #ccc;
    padding: 6px 12px;
    cursor: pointer;
    color: white;
    display: inline-block;
    margin-bottom: 20px;
}

button {
    display: block;
    width: 100%;
    padding: 10px 20px;
    background-color: blue;
    color: white;
    border: none;
    border-radius: 4px;
}
button:hover {
    background-color: #1e90ff;
    cursor: pointer;
}
</style>
