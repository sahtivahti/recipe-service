const express = require('express')
const router = express.Router()

router.use(require('./recipe'))

module.exports = router
