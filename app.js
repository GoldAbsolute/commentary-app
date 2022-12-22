const App = {
    data() {
        return {
            apiUrlGet: "http://" + window.location.host + "/api/get",
            apiUrlPost: "http://" + window.location.host + "/api/post",
            comments: [],
            errors: [],
            form: {
                name: "",
                text: ""
            },
            secretCaptcha: '',
            secretCaptchaUrl: '',
            captcha: "",
            errorClasses: 'display-none'
        }
    },
    mounted() {
        //
        console.log(this.apiUrlGet)
        console.log(this.apiUrlPost)
        //
        this.getComments()
        //
    },
    methods: {
        async getComments() {
            try {
                const response = await fetch(this.apiUrlGet);
                const result = await response.json();
                this.comments.push(...result);
                console.log(this.comments)
            } catch (error) {
                this.errors.push(error);
                console.log(this.errors)
            }
        },
        async updateComments() {
            try {
                const response = await fetch(this.apiUrlGet);
                const result = await response.json();
                this.comments.length = 0
                this.comments.push(...result);
                console.log(this.comments)
            } catch (error) {
                this.errors.push(error);
                console.log(this.errors)
            }
        },
    }
}

Vue.createApp(App).mount('.app')