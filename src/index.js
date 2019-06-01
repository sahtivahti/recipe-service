const express = require('express')
const app = express()

const v1Router = require('./routes/v1')
app.use(v1Router)

const port = process.env.PORT || 3000
app.listen(port, () => console.log(`App listening on ${port}`))
