import React, { Component } from 'react'
import { DragDropContext, Draggable, Droppable } from 'react-beautiful-dnd'
import axios from 'axios'
import DealModel from '../models/DealModel'
import LeadModel from '../models/LeadModel'
import TaskModel from '../models/TaskModel'
import queryString from 'query-string'
import { taskTypes } from '../utils/_consts'
import { Col, Row } from 'reactstrap'
import ViewEntity from '../common/ViewEntity'
import EditTask from './edit/EditTask'

export default class KanbanNew extends Component {
    constructor (props) {
        super(props)

        this.state = {
            type: queryString.parse(this.props.location.search).type || 'task',
            columns: {},
            entities: {},
            statuses: {},
            customers: {},
            view: {
                viewMode: false,
                edit: false,
                viewedId: false
            }
        }

        this.formatColumns = this.formatColumns.bind(this)
        this.save = this.save.bind(this)
        this.getStatuses = this.getStatuses.bind(this)
        this.getCustomers = this.getCustomers.bind(this)
        this.toggleViewedEntity = this.toggleViewedEntity.bind(this)
    }

    componentDidMount () {
        this.getStatuses().then(() => {
            if (this.state.type === 'task') {
                this.getTasks()
            }

            if (this.state.type === 'lead') {
                this.getLeads()
            }

            if (this.state.type === 'deal') {
                this.getDeals()
            }

            this.getCustomers()
        })
    }

    getCustomers () {
        axios.get('api/customers')
            .then((r) => {
                this.setState({ customers: r.data })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    async getStatuses () {
        const task_type = taskTypes[this.state.type]
        try {
            const res = await axios.get(`/api/taskStatus?task_type=${task_type}`)

            if (res.status === 200) {
                // test for status you want, etc
                console.log(res.status)
            }
            // Don't forget to return something

            this.setState({ statuses: res.data })
            return res.data
        } catch (e) {
            this.handleError(e)
            return false
        }
    }

    getTasks () {
        axios.get('api/tasks')
            .then((r) => {
                this.setState({ entities: r.data }, () => {
                    this.formatColumns()
                })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    getLeads () {
        axios.get('api/leads')
            .then((r) => {
                this.setState({ entities: r.data }, () => {
                    this.formatColumns()
                })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    getDeals () {
        axios.get('api/deals')
            .then((r) => {
                this.setState({ entities: r.data }, () => {
                    this.formatColumns()
                })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    save (element, status) {
        console.log('element', element)

        element.task_status = status
        element.id = parseInt(element.id)

        let model

        switch (this.state.type) {
            case 'task':
                model = new TaskModel(element)
                break

            case 'deal':
                model = new DealModel(element)
                break

            case 'lead':
                model = new LeadModel(element)
                break
        }

        model.update(element).then(response => {
            if (!response) {
                this.setState({
                    showErrorMessage: true,
                    loading: false,
                    errors: this.model.errors,
                    message: this.model.error_message
                })
            }
        })
    }

    toggleViewedEntity (id, title, edit, entity) {
        if (!entity) {
            this.setState({
                view: {
                    ...this.state.view,
                    viewMode: false
                }
            }, () => {
                this.setState({
                    view: {
                        ...this.state.view,
                        edit: edit
                    }
                })
            })

            return
        }

        this.setState({
            view: {
                ...this.state.view,
                viewedId: entity,
                viewMode: !this.state.view.viewMode,
                title: entity.name
            }
        }, () => {
            this.setState({ edit: edit })
        })
    }

    formatColumns () {
        const columns = []

        this.state.statuses.map((entity, index) => {
            if (!columns[entity.id]) {
                columns[entity.id] = {
                    name: entity.name,
                    items: []
                }
            }
        })

        console.log('columns', columns)

        this.state.entities.map((entity, index) => {
            entity.id = entity.id.toString()

            columns[entity.task_status].items.push(entity)
        })

        this.setState({ columns: columns })
    }

    onDragEnd (result, columns, setColumns) {
        if (!result.destination) return
        const { source, destination } = result

        if (source.droppableId !== destination.droppableId) {
            const sourceColumn = columns[source.droppableId]
            const destColumn = columns[destination.droppableId]
            const sourceItems = [...sourceColumn.items]
            const destItems = [...destColumn.items]

            const entity = sourceItems[source.index]

            const [removed] = sourceItems.splice(source.index, 1)
            destItems.splice(destination.index, 0, removed)

            columns[source.droppableId] = {
                ...sourceColumn,
                items: sourceItems
            }

            columns[destination.droppableId] = {
                ...destColumn,
                items: destItems
            }

            this.setState({ columns: columns }, () => {
                this.save(entity, destination.droppableId)
            })
        } else {
            const column = columns[source.droppableId]
            const copiedItems = [...column.items]
            const [removed] = copiedItems.splice(source.index, 1)
            copiedItems.splice(destination.index, 0, removed)

            columns[source.droppableId] = {
                ...column,
                items: copiedItems
            }

            const taskIds = []

            this.setState({ columns: columns }, () => {
                const columns = this.state.columns
                const column = columns[source.droppableId]
                column.items.map((entity, index) => {
                    column.items[index].task_sort_order = (index + 1)
                    taskIds.push(entity.id)
                })

                columns[source.droppableId] = column

                this.setState({ columns: columns }, () => {
                    console.log('sort', this.state.columns)
                })
            })
        }
    }

    // Normally you would want to split things out into separate components.
    // But in this example everything is just done in one place for simplicity
    render () {
        const { columns, customers, entities } = this.state
        const edit = this.state.type === 'task' && this.state.view.viewedId
            ? <EditTask listView={true} modal={true} show={this.state.view.edit} tasks={this.props.entities}
                task={this.state.view.viewedId}/> : null

        return customers.length && columns.length && entities.length ? (
            <React.Fragment>
                <Row>
                    <Col className="w-100 overflow-auto pr-2" sm={12}>
                        <div style={{ display: 'flex', height: '100%' }}>
                            <DragDropContext
                                onDragEnd={result => this.onDragEnd(result, columns)}
                            >
                                {Object.entries(columns).map(([columnId, column], index) => {
                                    return (
                                        <div
                                            style={{
                                                display: 'flex',
                                                flexDirection: 'column',
                                                alignItems: 'center'
                                            }}
                                            key={columnId}
                                        >
                                            <h4>{column.name}</h4>
                                            <div style={{ margin: 8 }}>
                                                <Droppable droppableId={columnId} key={columnId}>
                                                    {(provided, snapshot) => {
                                                        return (
                                                            <div
                                                                {...provided.droppableProps}
                                                                ref={provided.innerRef}
                                                                style={{
                                                                    background: snapshot.isDraggingOver
                                                                        ? 'lightblue'
                                                                        : 'lightgrey',
                                                                    padding: 4,
                                                                    width: 250,
                                                                    minHeight: 500
                                                                }}
                                                            >
                                                                {column.items.map((item, index) => {
                                                                    return (
                                                                        <Draggable
                                                                            key={item.id}
                                                                            draggableId={item.id}
                                                                            index={index}
                                                                        >
                                                                            {(provided, snapshot) => {
                                                                                return (
                                                                                    <div
                                                                                        ref={provided.innerRef}
                                                                                        {...provided.draggableProps}
                                                                                        {...provided.dragHandleProps}
                                                                                        style={{
                                                                                            userSelect: 'none',
                                                                                            padding: 16,
                                                                                            margin: '0 0 8px 0',
                                                                                            minHeight: '50px',
                                                                                            backgroundColor: snapshot.isDragging
                                                                                                ? '#263B4A'
                                                                                                : '#456C86',
                                                                                            color: 'white',
                                                                                            ...provided.draggableProps.style
                                                                                        }}
                                                                                    >
                                                                                        <a style={{ padding: '12px' }}
                                                                                            onClick={(e) => {
                                                                                                this.toggleViewedEntity(null, null, false, item)
                                                                                            }}>{item.name}</a>
                                                                                    </div>
                                                                                )
                                                                            }}
                                                                        </Draggable>
                                                                    )
                                                                })}
                                                                {provided.placeholder}
                                                            </div>
                                                        )
                                                    }}
                                                </Droppable>
                                            </div>
                                        </div>
                                    )
                                })}
                            </DragDropContext>
                        </div>
                    </Col>
                </Row>

                <ViewEntity
                    edit={edit}
                    toggle={this.toggleViewedEntity}
                    title={this.state.view.title}
                    viewed={this.state.view.viewMode}
                    customers={this.state.customers}
                    entity={this.state.view.viewedId}
                    entity_type={this.state.type.charAt(0).toUpperCase() + this.state.type.slice(1)}
                />

            </React.Fragment>

        ) : <div>Loading</div>
    }
}
