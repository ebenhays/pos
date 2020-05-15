using PointOfSale.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PointOfSale.Data.interfaces
{
	interface ICategory
	{
		IEnumerable<Category> GetCategories();
		void AddCategory(Category category);
		Category GetCategory(int id);
		void UpdateCategory(Category category,int id);
		void DeleteCategory(int id);
	}
}
