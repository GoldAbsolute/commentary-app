const App = {
    data() {
        return {
            apiUrlGet: "http://" + window.location.host + "/api/get",
            apiUrlPost: "http://" + window.location.host + "/api/post",
            apiUrlDelete: "http://" + window.location.host + "api/delete/",
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
        console.log(this.apiUrlDelete)
        //
        this.getComments()
        this.captchaGenerate()
        this.captchaGenerateUrl()
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
        async addComment() {
            if (this.captcha == this.secretCaptcha) {
                try {
                    console.log("Отправка комментария")
                    console.log(this.form)
                    const result = await fetch(this.apiUrlPost, {
                        method: 'POST',
                        body: JSON.stringify({
                            name: this.form.name,
                            text: this.form.text
                        }),
                        headers: {
                            'Content-type': 'application/json; charset=utf-8',
                        },
                        credentials: "include",
                    })
                    const data = await result.json()
                    console.log(data)
                } catch (error) {
                    console.error(error)
                }
                this.form.name = '';
                this.form.text = '';
                await this.updateComments()
                //
                this.captcha = ''
                this.captchaGenerate()
                this.captchaGenerateUrl()
            } else {
                this.errorClasses='captcha-error'
                setTimeout(this.hideErrorCaptcha, 20000)
                // Update captcha
                this.captcha = ''
                this.captchaGenerate()
                this.captchaGenerateUrl()
            }

        },
        async deleteComment(comment) {
            try {
                console.log("Удаление комментария")
                console.log(comment.id)
                const result = await fetch(this.apiUrlDelete+comment.id, {
                    method: 'DELETE',
                    body: JSON.stringify({
                        id: comment.id
                    }),
                    headers: {
                        'Content-type': 'application/json; charset=utf-8',
                    },
                    credentials: "include",
                })
                const data = await result.json()
                console.log(data)
            } catch (error) {
                console.error(error)
            }
            await this.updateComments()
        },
        captchaGenerate() {
            let captcha = '';
            i = 6;
            while (captcha.length < i)
                captcha += Math.random().toString(36).substring(2);
            // string
            secretCaptcha = captcha.substring(0, i);
            this.secretCaptcha = secretCaptcha;
        },
        captchaGenerateUrl() {
            this.secretCaptchaUrl = 'https://dummyimage.com/200x100/000/fff&text=' + this.secretCaptcha;
        },
        hideErrorCaptcha() {
            this.errorClasses = 'display-none'
        },
    }
}

Vue.createApp(App).mount('.app')