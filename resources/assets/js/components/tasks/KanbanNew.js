import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd'
import axios from 'axios'
import DealModel from '../models/DealModel'
import LeadModel from '../models/LeadModel'
import TaskModel from '../models/TaskModel'
import queryString from 'query-string'
import { taskTypes } from '../utils/_consts'
import { Row, Col } from 'reactstrap'

export default class KanbanNew extends Component {
    constructor (props) {
        super(props)

        this.state = {
            type: queryString.parse(this.props.location.search).type || '',
            columns: {},
            entities: {},
            statuses: {}
        }

        this.formatColumns = this.formatColumns.bind(this)
        this.save = this.save.bind(this)
        this.getStatuses = this.getStatuses.bind(this)
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
            entity.content = entity.name

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
            this.setState({
                ...columns,
                [source.droppableId]: {
                    ...column,
                    items: copiedItems
                }
            })
        }
    }

    // Normally you would want to split things out into separate components.
    // But in this example everything is just done in one place for simplicity
    render () {
        const { columns } = this.state

        return (
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
                                                                                    {item.content}
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

        )
    }
}
