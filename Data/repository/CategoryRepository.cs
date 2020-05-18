using PointOfSale.Data.interfaces;
using PointOfSale.Models;
using PointOfSale.Models.ViewModels;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using System.Web;

namespace PointOfSale.Data.repository
{
	public class CategoryRepository : ICategory
	{
		private readonly POSContext _context = new POSContext();

		//public CategoryRepository(POSContext context)
		//{
		//	_context = context;
		//}
		public void AddCategory(Category category)
		{
			if (category == null)
			{
				throw new ArgumentNullException(nameof(category));
			}
			var savecategory = new Category()
			{
				Name = category.Name,
				CreatedBy = "",
				CreationDate = DateTime.Now,
				LastUpdateDate = null,
				LastUpdatedBy = ""

			};
			_context.Categories.Add(savecategory);
			_context.SaveChanges();
		}

		public void DeleteCategory(int id)
		{
			var findUser = _context.Categories.FirstOrDefault(p => p.Id == id);
			if (findUser != null)
			{
				_context.Categories.Remove(findUser);
				_context.SaveChanges();
			}
		}

		public IEnumerable<Category> GetCategories()
		{
			return _context.Categories.ToList();
			
		}

		public Category GetCategory(int id)
		{
			var cat = _context.Categories.FirstOrDefault(p => p.Id == id);
			return cat;
		}

		public void UpdateCategory(Category category, int id)
		{
			try
			{
				Category cat = _context.Categories.FirstOrDefault(p => p.Id == id);
				cat.Name = category.Name;
				cat.LastUpdateDate = DateTime.Now;
				cat.LastUpdatedBy = "";
				_context.SaveChanges();

			}
			catch (Exception)
			{

				throw;
			}
			
		}
	}
}