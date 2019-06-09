const recipes = require('../../../services/recipes')

module.exports = (req, res) => {
  const { id } = req.params

  recipes.remove(id)

  res.json({ id })
}
