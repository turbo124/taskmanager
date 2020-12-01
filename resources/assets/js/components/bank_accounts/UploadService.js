import axios from 'axios'

class UploadService {
    async export (import_type, data) {
        try {
            this.errors = []
            this.error_message = ''
            const res = await axios.post(`/api/export?export_type=${import_type}`, data)

            if (res.status === 200) {
                // test for status you want, etc
                console.log(res.status)
            }
            // Don't forget to return something
            return res.data
        } catch (e) {
            this.handleError(e)
            return false
        }
    }

    async save (data) {
        try {
            this.errors = []
            this.error_message = ''
            const res = await axios.post('api/bank_accounts/ofx/import', data)

            if (res.status === 200) {
                // test for status you want, etc
                console.log(res.status)
            }
            // Don't forget to return something
            return res.data
        } catch (e) {
            this.handleError(e)
            return false
        }
    }

    handleError (error) {
        if (error.response && error.response.data.message) {
            this.error_message = error.response.data.message
        }

        if (error.response.data.errors) {
            this.errors = error.response.data.errors
        }
    }

    upload (file, url, import_type, onUploadProgress) {
        const formData = new FormData()

        formData.append('file', file)
        formData.append('import_type', import_type)

        return axios.post(url, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            onUploadProgress
        })
    }

    getFiles () {
        return axios.get('/files')
    }
}

export default new UploadService()
