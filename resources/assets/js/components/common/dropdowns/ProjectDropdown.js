import React, { Component } from 'react'
import axios from 'axios'
import { Input } from 'reactstrap'
import { translations } from '../../utils/_translations'

export default class ProjectDropdown extends Component {
    constructor (props) {
        super(props)
        this.state = {
            projects: []
        }

        this.getProducts = this.getProducts.bind(this)
    }

    componentDidMount () {
        if (!this.props.projects || !this.props.projects.length) {
            this.getProducts()
        } else {
            this.setState({ projects: this.props.projects })
        }
    }

    getProducts () {
        axios.get('/api/projects')
            .then((r) => {
                this.setState({
                    projects: r.data
                })
            })
            .catch((e) => {
                console.error(e)
            })
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

    buuildMultiple (name, projectList) {
        return (
            <Input value={this.props.project} onChange={this.props.handleInputChanges} type="select" multiple
                name={name} id={name}>
                {projectList}
            </Input>
        )
    }

    buildSingle (name, projectList, dataId) {
        return (
            <Input data-line={dataId} value={this.props.project} onChange={this.props.handleInputChanges} type="select"
                name={name} id={name}>
                <option value="">{translations.select_option}</option>
                {projectList}
            </Input>
        )
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback d-block'>
                    <strong>{this.props.errors[field][0]}</strong>
                </span>
            )
        }
    }

    hasErrorFor (field) {
        return this.props.errors && !!this.props.errors[field]
    }

    render () {
        let projectList = null
        if (!this.state.projects.length) {
            projectList = <option value="">Loading...</option>
        } else {
            projectList = this.state.projects.map((project, index) => (
                <option key={index} value={project.id}>{project.name}</option>
            ))
        }

        const name = this.props.name && this.props.name ? this.props.name : 'project_id'
        const dataId = this.props.dataId ? this.props.dataId : 0
        const input = this.props.multiple && this.props.multiple === true ? this.buuildMultiple(name, projectList) : this.buildSingle(name, projectList, dataId)

        return (
            <React.Fragment>
                {input}
                {this.renderErrorFor('project_id')}
            </React.Fragment>
        )
    }
}
