const recipes = require('../../../services/recipes')

module.exports = (req, res) => {
  const list = recipes.list()
  res.json(list)
}
