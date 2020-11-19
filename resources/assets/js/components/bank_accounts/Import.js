import React from 'react'
import UploadService from './UploadService'
import ImportPreview from './ImportPreview'
import { translations } from '../utils/_translations'

export default class Import extends React.Component {
    constructor () {
        super()
        this.state = {
            selectedFiles: null,
            currentFile: null,
            progress: 0,
            message: '',
            fileInfos: [],
            loading: false,
            checked: new Set()
        }

        this.selectFile = this.selectFile.bind(this)
        this.upload = this.upload.bind(this)
    }

    componentDidMount () {
        UploadService.getFiles().then((response) => {
            this.setState({
                fileInfos: response.data
            })
        })
    }

    selectFile (event) {
        this.setState({
            selectedFiles: event.target.files
        })
    }

    upload () {
        const currentFile = this.state.selectedFiles[0]

        this.setState({
            progress: 0,
            currentFile: currentFile
        })

        UploadService.upload(currentFile, (event) => {
            this.setState({
                progress: Math.round((100 * event.loaded) / event.total)
            })
        })
            .then((response) => {
                this.setState({
                    fileInfos: response.data,
                    progress: 0,
                    currentFile: undefined
                })
                // return UploadService.getFiles()
            })
            .catch((e) => {
                alert(e)
                this.setState({
                    progress: 0,
                    message: 'Could not upload the file!',
                    currentFile: undefined
                })
            })

        this.setState({
            selectedFiles: undefined
        })
    }

    save () {
        const data = { checked: Array.from(this.state.checked), data: this.state.fileInfos}

        UploadService.save(data).then(response => {
            if (!response) {
                console.log('error', response)
                return
            }

            console.log('success', response)
        })
    }

    handleChange (event, column, row, index) {
        const data = this.state.fileInfos
        data[index][column] = event.target.value

        this.setState({ fileInfos: data })
        console.log('data', data)
    }

    render () {
        const {
            selectedFiles,
            currentFile,
            progress,
            message,
            fileInfos,
            loading
        } = this.state

        return (
            <div className="row">
                <div className="col-12">
                    <div className="card mt-2">
                        <div className="card-body">
                            {currentFile && (
                                <div className="progress">
                                    <div
                                        className="progress-bar progress-bar-info progress-bar-striped"
                                        role="progressbar"
                                        aria-valuenow={progress}
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        style={{ width: progress + '%' }}
                                    >
                                        {progress}%
                                    </div>
                                </div>
                            )}

                            <label className="btn btn-default">
                                <input type="file" onChange={this.selectFile} />
                            </label>

                            <button className="btn btn-success"
                                disabled={!selectedFiles}
                                onClick={this.upload}
                            >
                                Upload
                            </button>

                            {!!message &&
                            <div className="alert alert-danger" role="alert">
                                {message}
                            </div>
                            }
                        </div>
                    </div>

                    {fileInfos.length &&
                    <div className="card mt-2">
                        <div className="card-header">List of Files</div>
                        <div className="card-body">
                            <ImportPreview
                                isCheckboxChecked={({ id }) => this.state.checked.has(id)}
                                onMasterCheckboxChange={(_, rows) => {
                                    let all = true
                                    const checked = this.state.checked

                                    rows.forEach(({ id }) => {
                                        if (!checked.has(id)) {
                                            all = false
                                        }
                                    })

                                    rows.forEach(({ id }) => {
                                        if (all) {
                                            checked.delete(id)
                                        } else if (!checked.has(id)) {
                                            checked.add(id)
                                        }
                                    })

                                    this.setState({ checked: checked }, () => {
                                        console.log('checked', this.state.checked)
                                    })
                                }}
                                onCheckboxChange={(_, { id }) => {
                                    const checked = this.state.checked

                                    if (checked.has(id)) {
                                        checked.delete(id)
                                    } else {
                                        checked.add(id)
                                    }

                                    this.setState({ checked: checked }, () => {
                                        console.log('checked', checked)
                                    })
                                }}
                                dataItemManipulator={(field, value) => {
                                    return value
                                }}
                                editableColumns={[
                                    {
                                        name: 'name',
                                        controlled: true,
                                        type: 'text',
                                        onChange: this.handleChange.bind(this)
                                    },
                                    {
                                        name: 'memo',
                                        controlled: true,
                                        type: 'text',
                                        onChange: this.handleChange.bind(this)
                                    }]}
                                disabledCheckboxes={[]}
                                renderMasterCheckbox={true}
                                rows={fileInfos}
                                totalRows={fileInfos.length}
                                currentPage={1}
                                perPage={50}
                                totalPages={1}
                                loading={loading}
                                noDataMessage={'No transactions found'}
                                allowOrderingBy={['date', 'name', 'amount', 'id']}
                                columnWidths={[]}
                                disallowOrderingBy={['userInitiatedDate', 'uniqueId']}
                                renderCheckboxes={true}
                                buttons={[]}
                                actions={[]}
                                fieldsToExclude={['userInitiatedDate', 'uniqueId', 'type']}
                                // changePage={this.changePage}
                                // changeOrder={this.changeOrder}
                                // changePerPage={this.changePerPage}
                                // disallowOrderingBy={this.disallowOrderingBy}
                                // footer={footer ? this.renderFooter : undefined}
                                // {...props}
                            />

                            <button className="btn btn-primary" onClick={this.save.bind(this)}>{translations.save}</button>
                        </div>

                    </div>
                    }
                </div>
            </div>
        )
    }
}
