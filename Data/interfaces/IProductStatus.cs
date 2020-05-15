using PointOfSale.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PointOfSale.Data.interfaces
{
	interface IProductStatus
	{
		IEnumerable<ProductStatus> GetProductStatus();
		void AddProductStatus(ProductStatus productStatus);
		ProductStatus GetStatus(int id);
		void UpdateProductStatus(ProductStatus productStatus);
		void DeleteProductStatus(int id);
	}
}
