const express = require('express')
const router = express.Router()

router.get('/v1/recipe', require('./search'))
router.post('/v1/recipe', require('./create'))
router.get('/v1/recipe/:id', require('./get'))
router.delete('/v1/recipe/:id', require('./delete'))

module.exports = router
