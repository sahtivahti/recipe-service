const queue = require('../../../util/queue')

module.exports = (req, res) => {
  const recipe = {
    id: 'foo',
    name: 'bar'
  }

  queue.create('create.recipe', recipe).save()

  res.json(recipe)
}
