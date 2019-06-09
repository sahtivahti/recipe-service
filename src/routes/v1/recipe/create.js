const queue = require('../../../util/queue')
const uuid = require('uuid/v4')

module.exports = (req, res) => {
  const recipe = {
    id: uuid(),
    ...req.body
  }

  queue.create('recipe.create', recipe).save()

  res.json(recipe)
}
