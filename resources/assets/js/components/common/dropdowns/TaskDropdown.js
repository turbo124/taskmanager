import React, { Component } from 'react'
import axios from 'axios'
import { FormGroup, Input } from 'reactstrap'
import Select from 'react-select'
import { translations } from '../../utils/_translations'

export default class TaskDropdown extends Component {
    constructor (props) {
        super(props)
        this.state = {
            tasks: []
        }

        this.getTasks = this.getTasks.bind(this)
    }

    componentDidMount () {
        if (!this.props.tasks || !this.props.tasks.length) {
            this.getTasks()
        } else {
            this.setState({ tasks: this.props.tasks })
        }
    }

    handleChange (value, name) {
        const e = {
            target: {
                id: name,
                name: name,
                value: value.id
            }
        }

        this.props.handleInputChanges(e)
    }

    getTasks () {
        axios.get('/api/tasks')
            .then((r) => {
                this.setState({
                    tasks: r.data
                }, function () {
                    if (!this.props.multiple) {
                        this.state.tasks.unshift({ id: '', title: 'Select Task' })
                    }
                })
            })
            .catch((e) => {
                console.error(e)
            })
    }

    render () {
        const name = this.props.name && this.props.name ? this.props.name : 'task_id'
        const task = this.props.task ? this.state.tasks.filter(option => option.id === this.props.task) : null
        const dataId = this.props.dataId ? this.props.dataId : 0

        let productList = null
        if (!this.state.tasks.length) {
            productList = <option value="">Loading...</option>
        } else {
            productList = this.state.tasks.map((task, index) => (
                <option key={index} value={task.id}>{task.name}</option>
            ))
        }

        return this.props.single_only ? <FormGroup className="ml-2">
            <Input data-line={dataId} value={this.props.task} onChange={this.props.handleInputChanges} type="select"
                name={name} id={name}>
                <option value="">{translations.select_option}</option>
                {productList}
            </Input>
        </FormGroup> : (
            <FormGroup className="ml-2">
                <Select
                    className="flex-grow-1"
                    classNamePrefix="select"
                    name={name}
                    value={task}
                    options={this.state.tasks}
                    getOptionLabel={option => option.name}
                    getOptionValue={option => option.id}
                    onChange={(value) => this.handleChange(value, name)}
                />
            </FormGroup>
        )
    }
}
