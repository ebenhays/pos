using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Security.Permissions;
using System.Web;
using System.Web.Mvc;

namespace PointOfSale.Models
{
	public class Category
	{
		[Key]
		public int Id { get; set; }
		[Required]
		[StringLength(100)]
		public string Name { get; set; }
		[Required]
		[StringLength(100)]
		[Index(IsUnique = true)]
		public string Code { get; set; }
		[Required]
		public DateTime CreationDate { get; set; }
		[Required]
		[StringLength(50)]
		public string CreatedBy { get; set; }
		public DateTime? LastUpdatedBy { get; set; }
		public string LastUpdateDate { get; set; }
	}
	public class Company
	{
		public int Id { get; set; }
		[Required]
		[StringLength(100)]
		public string Name { get; set; }
		[StringLength(100)]
		public string Address { get; set; }
		[Required]
		public string ContactNo { get; set; }
		[StringLength(50)]
		public string EmailAddress { get; set; }
		public DateTime CreationDate { get; set; }
		[Required]
		[StringLength(50)]
		public string CreatedBy { get; set; }
		public DateTime? LastUpdatedBy { get; set; }
		public string LastUpdateDate { get; set; }
	}
	public class ProductStatus
	{
		public int Id { get; set; }
		[Required]
		[StringLength(50)]
		public string Status { get; set; }
		[Required]
		public DateTime CreationDate { get; set; }
		[Required]
		[StringLength(50)]
		public string CreatedBy { get; set; }
		public DateTime? LastUpdatedBy { get; set; }
		public string LastUpdateDate { get; set; }
	}
	public class UserRole
	{
		public int Id { get; set; }
		[Required]
		[StringLength(50)]
		public string Name { get; set; }
		[Required]
		public DateTime CreationDate { get; set; }
		[Required]
		[StringLength(50)]
		public string CreatedBy { get; set; }
		public DateTime? LastUpdatedBy { get; set; }
		public string LastUpdateDate { get; set; }
	}
	public class UserStatus
	{
		public int Id { get; set; }

		[Required]
		[StringLength(50)]
		public string Status { get; set; }
		[Required]
		public DateTime CreationDate { get; set; }
		[Required]
		[StringLength(50)]
		public string CreatedBy { get; set; }
		public DateTime? LastUpdatedBy { get; set; }
		public string LastUpdateDate { get; set; }
	}
	public class Suppliers
	{
		public int Id { get; set; }
		[Required]
		[StringLength(100)]
		public string Name { get; set; }
		[Required]
		public string Code { get; set; }
		public string Address { get; set; }
		[Required]
		public string ContactNo { get; set; }
		[Required]
		public DateTime CreationDate { get; set; }
		[Required]
		[StringLength(50)]
		public string CreatedBy { get; set; }
		public DateTime? LastUpdatedBy { get; set; }
		public string LastUpdateDate { get; set; }
	}
	public class Users
	{
		public int Id { get; set; }
		[Required]
		[StringLength(50)]
		[Index(IsUnique = true)]
		public string UserName { get; set; }
		[Required]
		public virtual UserRole UserRole { get; set; }

		[Required]
		public virtual UserStatus UserStatus { get; set; }

		[Required]
		[StringLength(100)]
		public string FullName { get; set; }
		[Required]
		public DateTime CreationDate { get; set; }
		[Required]
		[StringLength(50)]
		public string CreatedBy { get; set; }
		public DateTime? LastUpdatedBy { get; set; }
		public string LastUpdateDate { get; set; }
	}
	public class Inventory
	{
		public int Id { get; set; }
		[Required]
		[Index(IsUnique = true)]
		[StringLength(100)]
		public string StockNo { get; set; }
		[Required]
		[StringLength(100)]
		public virtual Suppliers Supplier { get; set; }
		[StringLength(50)]
		public string BarcodeNo { get; set; }
		[Required]
		public virtual Category Category { get; set; }
		public string UnitType { get; set; }
		[Required]
		public float CostPrice { get; set; }
		[Required]
		public float PricePerItem { get; set; }
		[Required]
		public float Discount { get; set; }
		[DatabaseGenerated(DatabaseGeneratedOption.Computed)]
		public float TotalCost { get; private set; }
		[Required]
		public float Vat { get; set; }
		[Required]
		public int TotalQty { get; set; }
		public DateTime ManufDate { get; set; }
		public DateTime ExpiryDate { get; set; }
		[Required]
		public int PromptDays { get; set; }
		[Required]
		public int MinLevelStock { get; set; }
		public string FDBNo { get; set; }
		[Required]
		public DateTime CreatedDate { get; set; }
		public string CreatedBy { get; set; }
		public string LastUpdatedBy { get; set; }
		public DateTime LastUpdateDate { get; set; }
	}
	public class Retail : Inventory
	{
		//unless there is a different an added implentation
	}

	public class WholeSale : Inventory
	{
		//unless there is a different an added implentation
	}

	
}