import { getSettingsIcon, icons } from './common/_icons'

const modules = JSON.parse(localStorage.getItem('modules'))

let is_admin = false

if (Object.prototype.hasOwnProperty.call(localStorage, 'appState')) {
    const account_id = JSON.parse(localStorage.getItem('appState')).user.account_id
    const user_account = JSON.parse(localStorage.getItem('appState')).accounts.filter(account => account.account_id === parseInt(account_id))
    is_admin = user_account && user_account.length && (parseInt(user_account[0].is_owner) === 1 || parseInt(user_account[0].is_admin) === 1)
}

const items = [
    {
        name: 'Dashboard',
        url: '/dashboard',
        icon: 'fa fa-dashboard'
    },

    {
        icon: 'fa fa-cog',
        name: 'Settings',
        children: [
            {
                name: 'Company Details',
                url: '/accounts',
                icon: `fa ${getSettingsIcon('accounts')}`
            },
            {
                name: 'Payment Terms',
                url: '/payment_terms',
                icon: `fa ${getSettingsIcon('payment_terms')}`
            },
            {
                name: 'Templates and Reminders',
                url: '/template-settings',
                icon: `fa ${getSettingsIcon('template-settings')}`
            },
            {
                name: 'Email Settings',
                url: '/email-settings',
                icon: `fa ${getSettingsIcon('email-settings')}`
            },
            {
                name: 'Online Payments',
                url: '/gateway-settings',
                icon: `fa ${getSettingsIcon('gateway-settings')}`
            },
            {
                name: 'Invoice and Quotes',
                url: '/invoice-settings',
                icon: `fa ${getSettingsIcon('invoice-settings')}`
            },
            {
                name: 'Products',
                url: '/product-settings',
                icon: `fa ${getSettingsIcon('product-settings')}`
            },
            {
                name: 'Generated Numbers',
                url: '/number-settings',
                icon: `fa ${getSettingsIcon('number-settings')}`
            },
            {
                name: 'Group',
                url: '/group-settings',
                icon: `fa ${getSettingsIcon('group-settings')}`
            },
            {
                name: 'Tax Rates',
                url: '/tax-rates',
                icon: `fa ${getSettingsIcon('tax-rates')}`
            },
            {
                name: 'Field Settings',
                url: '/field-settings',
                icon: `fa ${getSettingsIcon('field-settings')}`
            },
            {
                name: 'Customer Portal Settings',
                url: '/portal-settings',
                icon: `fa ${getSettingsIcon('portal-settings')}`
            },
            {
                name: 'Designs',
                url: '/designs',
                icon: `fa ${getSettingsIcon('designs')}`
            },
            {
                name: 'Integrations',
                url: '/integrations',
                icon: `fa ${getSettingsIcon('integrations')}`
            },
            {
                name: 'Account Management',
                url: '/modules',
                icon: `fa ${getSettingsIcon('modules')}`
            },
            {
                name: 'Localisation',
                url: '/localisation',
                icon: `fa ${getSettingsIcon('localisation')}`
            },
            {
                name: 'Workflow Settings',
                url: '/workflow-settings',
                icon: `fa ${getSettingsIcon('workflow-settings')}`
            },
            {
                name: 'Device Settings',
                url: '/device-settings',
                icon: `fa ${getSettingsIcon('device-settings')}`
            }
            // {
            //     name: 'Subscriptions',
            //     url: '/subscriptions',
            //     icon: 'fa fa-dashboard'
            // },
            // {
            //     name: 'Tokens',
            //     url: '/tokens',
            //     icon: 'fa fa-dashboard'
            // }
        ]
    }
]

const financial = {
    name: 'Financial',
    icon: 'fa fa-bar-chart',
    children: [
        {
            name: 'Promocodes',
            url: '/promocodes',
            icon: 'fa fa-shopping-basket'
        }
    ]
}

if (modules && modules.invoices) {
    financial.children.push(
        {
            name: 'Invoices',
            url: '/invoice',
            icon: 'fa fa-area-chart'
        }
    )
}

if (modules && modules.orders) {
    financial.children.push(
        {
            name: 'Orders',
            url: '/orders',
            icon: `fa ${icons.order}`
        }
    )
}

if (modules && modules.quotes) {
    financial.children.push(
        {
            name: 'Quotes',
            url: '/quotes',
            icon: 'fa fa-handshake-o'
        }
    )
}

if (modules && modules.recurringInvoices) {
    financial.children.push(
        {
            name: 'Recurring Invoices',
            url: '/recurring-invoices',
            icon: 'fa fa-handshake-o'
        }
    )
}

if (modules && modules.recurringQuotes) {
    financial.children.push(
        {
            name: 'Recurring Quotes',
            url: '/recurring-quotes',
            icon: 'fa fa-handshake-o'
        }
    )
}

if (modules && modules.payments) {
    financial.children.push(
        {
            name: 'Payments',
            url: '/payments',
            icon: `fa ${icons.credit_card}`
        }
    )
}

if (modules && modules.expenses) {
    financial.children.push(
        {
            name: 'Expenses',
            url: '/expenses',
            icon: `fa ${icons.expense}`
        }
    )
}

items.push(financial)

const tasks = {
    name: 'Tasks',
    icon: `fa ${icons.task}`,
    children: []
}

if (modules && modules.leads) {
    tasks.children.push({
        name: 'Leads',
        url: '/leads',
        icon: 'fa fa-chain-broken'
    })
}

if (modules && modules.cases) {
    tasks.children.push({
        name: 'Cases',
        url: '/cases',
        icon: 'fa fa-chain-broken'
    })
}

if (modules && modules.projects) {
    tasks.children.push({
        name: 'Projects',
        url: 'projects',
        icon: `fa ${icons.project}`
    })
}

if (modules && modules.tasks) {
    tasks.children.push({
        name: 'Tasks',
        url: 'tasks',
        icon: `fa ${icons.task}`
    })

    tasks.children.push(
        {
            name: 'Task Statuses',
            url: '/statuses',
            icon: 'fa fa-building'
        }
    )
}

if (modules && modules.deals) {
    tasks.children.push(
        {
            name: 'Deals',
            url: '/kanban/deals',
            icon: 'fa fa-chain-broken'
        }
    )
}

items.push(tasks)

const users = {
    name: 'Users',
    icon: `fa ${icons.user}`,
    children: [
        {
            name: 'Employees',
            url: '/users',
            icon: `fa ${icons.user}`
        },

        {
            name: 'Departments',
            url: '/departments',
            icon: 'fa fa-sitemap'
        },

        {
            name: 'Roles',
            url: '/roles',
            icon: 'fa fa-chain-broken'
        },

        {
            name: 'Permissions',
            url: '/permissions',
            icon: 'fa fa-list-alt'
        }
    ]
}

if (is_admin) {
    items.push(users)
}

if (modules && modules.companies) {
    items.push(
        {
            name: 'Companies',
            url: '/companies',
            icon: `fa ${icons.building}`
        }
    )
}

const products = {
    name: 'Products',
    icon: 'fa fa-barcode',
    children: [
        {
            name: 'Products',
            url: '/products',
            icon: `fa ${icons.product}`
        },
        {
            name: 'Categories',
            url: '/categories',
            icon: 'fa fa-building'
        },
        {
            name: 'Brands',
            url: '/brands',
            icon: 'fa fa-building'
        },
        {
            name: 'Attributes',
            url: '/attributes',
            icon: 'fa fa-building'
        }
    ]
}

if (modules && modules.products) {
    items.push(products)
}

if (modules && modules.events) {
    items.push({
        name: 'Calendar',
        url: '/calendar',
        icon: 'fa fa-calendar'
    })
}

items.push({
    name: 'Customers',
    url: '/customers',
    icon: 'fa fa-address-book-o'
})

items.push(
    {
        name: 'Chat',
        url: '/chat',
        icon: 'fa fa-chain-broken'
    }
)

export default {
    items: items
}
