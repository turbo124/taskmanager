import React, { Component, Suspense } from 'react'
import { Redirect, Switch } from 'react-router-dom'
import * as router from 'react-router-dom'
import { Container } from 'reactstrap'
import PrivateRoute from '../../PrivateRoute'

import {
    AppAside,
    AppHeader,
    AppSidebar,
    AppSidebarFooter,
    AppSidebarForm,
    AppSidebarHeader,
    AppSidebarMinimizer,
    AppBreadcrumb2 as AppBreadcrumb,
    AppSidebarNav2 as AppSidebarNav
} from '@coreui/react'
// sidebar nav config
import navigation from '../../_nav'
// routes config
import routes from '../../routes'
import DefaultAside from './DefaultAside'
import DefaultHeader from './DefaultHeader'
import AccountList from '../../common/AccountList'
import Footer from '../../common/Footer'
import DefaultFooter from './DefaultFooter'

class DefaultLayout extends Component {
    constructor (props) {
        super(props)
        this.loading = <div className="animated fadeIn pt-1 text-center">Loading...</div>
    }

    signOut (e) {
        e.preventDefault()
        this.props.history.push('/login')
    }

    render () {
        return (
            <div className="app">
                <AppHeader fixed>
                    <Suspense fallback={this.loading}>
                        <DefaultHeader onLogout={e => this.signOut(e)}/>
                    </Suspense>
                </AppHeader>
                <div className="app-body">
                    <AppSidebar fixed display="lg">
                        <AppSidebarHeader/>
                        <AppSidebarForm/>
                        <AccountList />
                        <Suspense>
                            <AppSidebarNav navConfig={navigation} {...this.props} router={router}/>
                        </Suspense>
                        <AppSidebarFooter/>
                        <AppSidebarMinimizer/>
                    </AppSidebar>
                    <main className="main">
                        {/* <AppBreadcrumb appRoutes={routes} router={router}/> */}
                        <Container>
                            <Suspense fallback={this.loading}>
                                <Switch>
                                    {routes.map((route, idx) => {
                                        return route.component ? (
                                            <PrivateRoute
                                                key={idx}
                                                path={route.path}
                                                exact={route.exact}
                                                name={route.name}
                                                component={route.component}
                                                render={props => (
                                                    <route.component {...props} />
                                                )}/>
                                        ) : (null)
                                    })}
                                    <Redirect from="/" to="/dashboard"/>
                                </Switch>
                            </Suspense>
                        </Container>

                    </main>
                    <AppAside fixed>
                        <Suspense fallback={this.loading}>
                            <DefaultAside/>
                        </Suspense>
                    </AppAside>
                </div>

                {/* <AppFooter> */}
                {/*    <Suspense> */}
                {/*        <DefaultFooter/> */}
                {/*    </Suspense> */}
                {/* </AppFooter> */}
            </div>
        )
    }
}

export default DefaultLayout
