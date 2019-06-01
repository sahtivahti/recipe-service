const express = require('express')
const router = express.Router()

router.get('/v1/recipe', require('./search'))

module.exports = router
