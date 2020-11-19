import axios from 'axios'

class UploadService {
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

    upload (file, onUploadProgress) {
        const formData = new FormData()

        formData.append('file', file)

        return axios.post('api/bank_accounts/ofx/preview', formData, {
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
