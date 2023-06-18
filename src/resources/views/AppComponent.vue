<template>
    <div class="container">
        <UploadCSV @isLoading="changeLoadingState" @notification="updateNotification"></UploadCSV>
        <span ref="notification" class="notification">{{ notification }}</span>
        <teleport to="body">
            <LoadingView v-if="isLoading"></LoadingView>
        </teleport>
    </div>
</template>

<script>
import UploadCSV from './UploadCSV.vue';
import LoadingView from './LoadingView.vue';

    export default {
        components: {UploadCSV, LoadingView},
        data() {
            return {
                isLoading: false,
                notification: null,
            };
        },
        methods:{
            changeLoadingState(state) {
                this.isLoading = state;
            },
            updateNotification(message) {
                this.notification = message;
            },
        },
        watch: {
            notification(value) {
                if (!value) return;
                this.$refs.notification.style.right = "1rem";
                setTimeout(() => {
                    this.$refs.notification.style.right = "-100%";
                }, 3000);
                setTimeout(() => {
                    this.notification = null;
                }, 4000);
            }
        }
    }
</script>
<style>
.container{
    background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.9), rgba(0, 0 , 0, 0.9)), url('../images/home.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100%;
}
.notification{
    position: fixed;
    top: 5rem;
    right: -100%;
    color: black;
    padding: 1rem;
    font-weight: bold;
    width: auto;
    background-color: lightskyblue;
    border-radius: 10px;
    transition: all 1s ease-in-out;
    text-align: center;
}
</style>

